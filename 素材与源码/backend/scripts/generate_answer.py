import argparse
import json
import torch
from transformers import GPT2LMHeadModel, GPT2Tokenizer

def generate_answer(question_embedding, context_embedding, model, tokenizer, device):
    try:
        # 准备输入
        input_text = f"根据以下上下文回答问题：\n"
        input_text += f"上下文：{json.dumps(context_embedding, ensure_ascii=False)}\n"
        input_text += f"问题：{json.dumps(question_embedding, ensure_ascii=False)}\n"
        input_text += "回答："
        
        # 编码输入
        inputs = tokenizer(input_text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 生成回答
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
            
            answer = tokenizer.decode(outputs[0], skip_special_tokens=True)
        
        # 提取回答部分
        answer = extract_answer(answer)
        
        return {
            'success': True,
            'answer': answer
        }
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

def extract_answer(answer_text):
    # 提取回答部分，去除输入文本
    answer_start = answer_text.find("回答：")
    if answer_start != -1:
        answer = answer_text[answer_start + 3:].strip()
    else:
        answer = answer_text.strip()
    
    return answer

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--input', required=True)
    args = parser.parse_args()
    
    # 解析输入
    input_data = json.loads(args.input)
    question_embedding = input_data['question']
    context_embedding = input_data['context']
    
    # 加载模型和tokenizer
    model_path = 'path/to/gpt/model'
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    
    tokenizer = GPT2Tokenizer.from_pretrained(model_path)
    model = GPT2LMHeadModel.from_pretrained(model_path)
    model.to(device)
    model.eval()
    
    result = generate_answer(question_embedding, context_embedding, model, tokenizer, device)
    print(json.dumps(result)) 