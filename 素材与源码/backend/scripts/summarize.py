import argparse
import json
import torch
from transformers import GPT2LMHeadModel, GPT2Tokenizer
from sklearn.feature_extraction.text import TfidfVectorizer
import numpy as np

def summarize_text(text, max_length, model, tokenizer, device):
    try:
        # 使用TF-IDF提取重要句子
        sentences = text.split('。')
        vectorizer = TfidfVectorizer()
        tfidf_matrix = vectorizer.fit_transform(sentences)
        sentence_scores = tfidf_matrix.sum(axis=1).A1
        top_sentences = np.argsort(sentence_scores)[-3:]
        important_sentences = [sentences[i] for i in sorted(top_sentences)]
        
        # 准备输入
        input_text = "摘要以下文本：\n" + '。'.join(important_sentences)
        
        # 编码输入
        inputs = tokenizer(input_text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 生成摘要
        with torch.no_grad():
            outputs = model.generate(
                **inputs,
                max_length=max_length,
                num_beams=4,
                temperature=0.7,
                top_k=50,
                top_p=0.9,
                do_sample=True,
                pad_token_id=tokenizer.eos_token_id
            )
            
            summary = tokenizer.decode(outputs[0], skip_special_tokens=True)
        
        return {
            'success': True,
            'summary': summary
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
    text = input_data['text']
    max_length = input_data.get('max_length', 200)
    
    # 加载模型和tokenizer
    model_path = 'path/to/gpt/model'
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    
    tokenizer = GPT2Tokenizer.from_pretrained(model_path)
    model = GPT2LMHeadModel.from_pretrained(model_path)
    model.to(device)
    model.eval()
    
    result = summarize_text(text, max_length, model, tokenizer, device)
    print(json.dumps(result)) 