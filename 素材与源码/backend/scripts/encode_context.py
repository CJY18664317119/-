import argparse
import json
import torch
from transformers import BertModel, BertTokenizer

def encode_context(context, model, tokenizer, device):
    try:
        # 将上下文转换为文本
        text = ' '.join([str(v) for v in context.values()])
        
        # 编码文本
        inputs = tokenizer(text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 获取嵌入
        with torch.no_grad():
            outputs = model(**inputs)
            embedding = outputs.last_hidden_state.mean(dim=1).cpu().numpy().tolist()[0]
        
        return {
            'success': True,
            'embedding': embedding
        }
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--input', required=True)
    args = parser.parse_args()
    
    # 解析输入
    input_data = json.loads(args.input)
    context = input_data['context']
    
    # 加载模型和tokenizer
    model_path = 'path/to/bert/model'
    tokenizer_path = 'path/to/bert/tokenizer'
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    
    tokenizer = BertTokenizer.from_pretrained(tokenizer_path)
    model = BertModel.from_pretrained(model_path)
    model.to(device)
    model.eval()
    
    result = encode_context(context, model, tokenizer, device)
    print(json.dumps(result)) 