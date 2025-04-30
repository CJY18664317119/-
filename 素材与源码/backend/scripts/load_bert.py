import argparse
import json
import torch
from transformers import BertModel, BertTokenizer

def load_bert_model(model_path, tokenizer_path, device):
    try:
        # 加载tokenizer
        tokenizer = BertTokenizer.from_pretrained(tokenizer_path)
        
        # 加载模型
        model = BertModel.from_pretrained(model_path)
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
    parser.add_argument('--tokenizer_path', required=True)
    parser.add_argument('--device', default='cpu')
    args = parser.parse_args()
    
    result = load_bert_model(args.model_path, args.tokenizer_path, args.device)
    print(json.dumps(result)) 