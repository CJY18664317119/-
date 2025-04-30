import argparse
import json
import torch
from transformers import GPT2LMHeadModel, GPT2Tokenizer

def generate_resources(context_embedding, model, tokenizer, device):
    try:
        # 准备输入
        input_text = f"根据以下上下文生成教学资源：\n"
        input_text += json.dumps(context_embedding, ensure_ascii=False)
        
        # 编码输入
        inputs = tokenizer(input_text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 生成教学资源
        with torch.no_grad():
            outputs = model.generate(
                **inputs,
                max_length=600,
                num_beams=4,
                temperature=0.7,
                top_k=50,
                top_p=0.9,
                do_sample=True,
                pad_token_id=tokenizer.eos_token_id
            )
            
            resources = tokenizer.decode(outputs[0], skip_special_tokens=True)
        
        # 解析教学资源
        resources = parse_resources(resources)
        
        return {
            'success': True,
            'resources': resources
        }
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

def parse_resources(resources_text):
    # 解析教学资源文本，提取各个部分
    sections = {
        '教学视频': '',
        '教学课件': '',
        '实验指导': '',
        '练习题': '',
        '参考资料': '',
        '扩展阅读': ''
    }
    
    for section in sections.keys():
        start = resources_text.find(section)
        if start != -1:
            end = resources_text.find('\n\n', start)
            if end == -1:
                end = len(resources_text)
            sections[section] = resources_text[start:end].strip()
    
    return sections

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--input', required=True)
    args = parser.parse_args()
    
    # 解析输入
    input_data = json.loads(args.input)
    context_embedding = input_data['context']
    
    # 加载模型和tokenizer
    model_path = 'path/to/gpt/model'
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    
    tokenizer = GPT2Tokenizer.from_pretrained(model_path)
    model = GPT2LMHeadModel.from_pretrained(model_path)
    model.to(device)
    model.eval()
    
    result = generate_resources(context_embedding, model, tokenizer, device)
    print(json.dumps(result)) 