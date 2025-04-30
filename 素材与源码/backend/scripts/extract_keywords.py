import argparse
import json
import torch
from transformers import BertModel, BertTokenizer
from sklearn.feature_extraction.text import TfidfVectorizer
import numpy as np
import jieba
import jieba.analyse

def extract_keywords(text, top_k, model, tokenizer, device):
    try:
        # 使用jieba进行分词
        words = jieba.lcut(text)
        
        # 使用TF-IDF提取关键词
        vectorizer = TfidfVectorizer(max_features=100)
        tfidf_matrix = vectorizer.fit_transform([' '.join(words)])
        feature_names = vectorizer.get_feature_names_out()
        tfidf_scores = tfidf_matrix.toarray()[0]
        
        # 使用TextRank提取关键词
        textrank_keywords = jieba.analyse.textrank(text, topK=top_k)
        
        # 使用BERT获取词嵌入
        inputs = tokenizer(text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        with torch.no_grad():
            outputs = model(**inputs)
            word_embeddings = outputs.last_hidden_state[0].cpu().numpy()
        
        # 计算词的重要性得分
        word_scores = {}
        for i, word in enumerate(words):
            if word in feature_names:
                tfidf_score = tfidf_scores[np.where(feature_names == word)[0][0]]
                embedding_norm = np.linalg.norm(word_embeddings[i])
                word_scores[word] = tfidf_score * embedding_norm
        
        # 选择top_k个关键词
        sorted_words = sorted(word_scores.items(), key=lambda x: x[1], reverse=True)
        selected_keywords = [word for word, _ in sorted_words[:top_k]]
        
        # 合并不同方法的结果
        all_keywords = list(set(selected_keywords + textrank_keywords))
        
        return {
            'success': True,
            'keywords': all_keywords[:top_k]
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
    top_k = input_data.get('top_k', 5)
    
    # 加载模型和tokenizer
    model_path = 'path/to/bert/model'
    tokenizer_path = 'path/to/bert/tokenizer'
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    
    tokenizer = BertTokenizer.from_pretrained(tokenizer_path)
    model = BertModel.from_pretrained(model_path)
    model.to(device)
    model.eval()
    
    result = extract_keywords(text, top_k, model, tokenizer, device)
    print(json.dumps(result)) 