import argparse
import json
import torch
from transformers import GPT2LMHeadModel, GPT2Tokenizer

def generate_courseware(data, model, tokenizer, device):
    try:
        # 准备输入
        input_text = f"根据以下信息生成课件：\n"
        input_text += json.dumps(data, ensure_ascii=False)
        
        # 编码输入
        inputs = tokenizer(input_text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 生成课件
        with torch.no_grad():
            outputs = model.generate(
                **inputs,
                max_length=800,
                num_beams=4,
                temperature=0.7,
                top_k=50,
                top_p=0.9,
                do_sample=True,
                pad_token_id=tokenizer.eos_token_id
            )
            
            courseware = tokenizer.decode(outputs[0], skip_special_tokens=True)
        
        # 解析课件
        courseware = parse_courseware(courseware)
        
        return {
            'success': True,
            'courseware': courseware
        }
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

def parse_courseware(courseware_text):
    # 解析课件文本，提取各个部分
    sections = {
        '封面': '',
        '目录': '',
        '教学内容': '',
        '课堂活动': '',
        '课后作业': '',
        '参考资料': ''
    }
    
    for section in sections.keys():
        start = courseware_text.find(section)
        if start != -1:
            end = courseware_text.find('\n\n', start)
            if end == -1:
                end = len(courseware_text)
            sections[section] = courseware_text[start:end].strip()
    
    return sections

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--input', required=True)
    args = parser.parse_args()
    
    # 解析输入
    input_data = json.loads(args.input)
    data = input_data['data']
    
    # 加载模型和tokenizer
    model_path = 'path/to/gpt/model'
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    
    tokenizer = GPT2Tokenizer.from_pretrained(model_path)
    model = GPT2LMHeadModel.from_pretrained(model_path)
    model.to(device)
    model.eval()
    
    result = generate_courseware(data, model, tokenizer, device)
    print(json.dumps(result)) 