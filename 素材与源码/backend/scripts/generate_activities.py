import argparse
import json
import torch
from transformers import GPT2LMHeadModel, GPT2Tokenizer

def generate_activities(scenario_embedding, model, tokenizer, device):
    try:
        # 准备输入
        input_text = f"根据以下项目情境生成四阶段活动设计：\n"
        input_text += json.dumps(scenario_embedding, ensure_ascii=False)
        
        # 编码输入
        inputs = tokenizer(input_text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 生成活动设计
        with torch.no_grad():
            outputs = model.generate(
                **inputs,
                max_length=400,
                num_beams=4,
                temperature=0.7,
                top_k=50,
                top_p=0.9,
                do_sample=True,
                pad_token_id=tokenizer.eos_token_id
            )
            
            activities = tokenizer.decode(outputs[0], skip_special_tokens=True)
        
        # 解析活动设计
        activities = parse_activities(activities)
        
        return {
            'success': True,
            'activities': activities
        }
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

def parse_activities(activities_text):
    # 解析活动设计文本，提取四个阶段的内容
    stages = ['准备阶段', '实施阶段', '评估阶段', '反思阶段']
    activities = {}
    
    for stage in stages:
        start = activities_text.find(stage)
        if start != -1:
            end = activities_text.find('\n\n', start)
            if end == -1:
                end = len(activities_text)
            activities[stage] = activities_text[start:end].strip()
    
    return activities

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--input', required=True)
    args = parser.parse_args()
    
    # 解析输入
    input_data = json.loads(args.input)
    scenario_embedding = input_data['scenario']
    
    # 加载模型和tokenizer
    model_path = 'path/to/gpt/model'
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    
    tokenizer = GPT2Tokenizer.from_pretrained(model_path)
    model = GPT2LMHeadModel.from_pretrained(model_path)
    model.to(device)
    model.eval()
    
    result = generate_activities(scenario_embedding, model, tokenizer, device)
    print(json.dumps(result)) 