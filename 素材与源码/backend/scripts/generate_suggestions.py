import argparse
import json
import torch
from transformers import GPT2LMHeadModel, GPT2Tokenizer

def generate_suggestions(context_embedding, suggestion_type, model, tokenizer, device):
    try:
        # 准备输入
        input_text = f"根据以下上下文生成{suggestion_type}建议：\n"
        input_text += json.dumps(context_embedding, ensure_ascii=False)
        
        # 编码输入
        inputs = tokenizer(input_text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 生成建议
        with torch.no_grad():
            outputs = model.generate(
                **inputs,
                max_length=200,
                num_beams=4,
                temperature=0.7,
                top_k=50,
                top_p=0.9,
                do_sample=True,
                pad_token_id=tokenizer.eos_token_id
            )
            
            suggestions = tokenizer.decode(outputs[0], skip_special_tokens=True)
        
        return {
            'success': True,
            'suggestions': suggestions
        }
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--input', required=True)
    parser.add_argument('--model_type', default='gpt')
    args = parser.parse_args()
    
    # 解析输入
    input_data = json.loads(args.input)
    context_embedding = input_data['context']
    suggestion_type = input_data['type']
    
    # 加载模型和tokenizer
    model_path = 'path/to/gpt/model'
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    
    tokenizer = GPT2Tokenizer.from_pretrained(model_path)
    model = GPT2LMHeadModel.from_pretrained(model_path)
    model.to(device)
    model.eval()
    
    result = generate_suggestions(context_embedding, suggestion_type, model, tokenizer, device)
    print(json.dumps(result)) 