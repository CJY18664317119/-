import argparse
import json
import torch
from transformers import GPT2LMHeadModel, GPT2Tokenizer

def generate_lesson_plan(data, model, tokenizer, device):
    try:
        # 准备输入
        input_text = f"根据以下信息生成教案：\n"
        input_text += json.dumps(data, ensure_ascii=False)
        
        # 编码输入
        inputs = tokenizer(input_text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 生成教案
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
            
            lesson_plan = tokenizer.decode(outputs[0], skip_special_tokens=True)
        
        # 解析教案
        lesson_plan = parse_lesson_plan(lesson_plan)
        
        return {
            'success': True,
            'lesson_plan': lesson_plan
        }
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

def parse_lesson_plan(lesson_plan_text):
    # 解析教案文本，提取各个部分
    sections = {
        '教学目标': '',
        '教学重点': '',
        '教学难点': '',
        '教学过程': '',
        '教学资源': '',
        '教学评价': ''
    }
    
    for section in sections.keys():
        start = lesson_plan_text.find(section)
        if start != -1:
            end = lesson_plan_text.find('\n\n', start)
            if end == -1:
                end = len(lesson_plan_text)
            sections[section] = lesson_plan_text[start:end].strip()
    
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
    
    result = generate_lesson_plan(data, model, tokenizer, device)
    print(json.dumps(result)) 