import argparse
import json
import torch
from transformers import GPT2LMHeadModel, GPT2Tokenizer

def load_gpt_model(model_path, device):
    try:
        # 加载tokenizer
        tokenizer = GPT2Tokenizer.from_pretrained(model_path)
        
        # 加载模型
        model = GPT2LMHeadModel.from_pretrained(model_path)
        model.to(device)
        model.eval()
        
        return {
            'success': True,
            'model': model,
            'tokenizer': tokenizer
        }
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--model_path', required=True)
    parser.add_argument('--device', default='cpu')
    args = parser.parse_args()
    
    result = load_gpt_model(args.model_path, args.device)
    print(json.dumps(result)) 