import argparse
import json
import torch
from transformers import BertModel, BertTokenizer
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.cluster import KMeans
import numpy as np

def analyze_text(text, model, tokenizer, device):
    try:
        # 编码文本
        inputs = tokenizer(text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 获取嵌入
        with torch.no_grad():
            outputs = model(**inputs)
            embedding = outputs.last_hidden_state.mean(dim=1).cpu().numpy()
        
        # 提取关键词
        vectorizer = TfidfVectorizer(max_features=100)
        tfidf_matrix = vectorizer.fit_transform([text])
        feature_names = vectorizer.get_feature_names_out()
        tfidf_scores = tfidf_matrix.toarray()[0]
        keywords = [feature_names[i] for i in np.argsort(tfidf_scores)[-5:]]
        
        # 主题聚类
        kmeans = KMeans(n_clusters=3, random_state=42)
        clusters = kmeans.fit_predict(embedding)
        
        # 情感分析
        sentiment = 'positive' if np.mean(embedding) > 0 else 'negative'
        
        return {
            'success': True,
            'analysis': {
                'embedding': embedding.tolist(),
                'keywords': keywords,
                'clusters': clusters.tolist(),
                'sentiment': sentiment
            }
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
    
    # 加载模型和tokenizer
    model_path = 'path/to/bert/model'
    tokenizer_path = 'path/to/bert/tokenizer'
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    
    tokenizer = BertTokenizer.from_pretrained(tokenizer_path)
    model = BertModel.from_pretrained(model_path)
    model.to(device)
    model.eval()
    
    result = analyze_text(text, model, tokenizer, device)
    print(json.dumps(result)) 