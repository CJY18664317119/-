import argparse
import json
import torch
from transformers import BertModel, BertTokenizer
import numpy as np
from sklearn.linear_model import LogisticRegression

def analyze_sentiment(text, model, tokenizer, device):
    try:
        # 编码文本
        inputs = tokenizer(text, return_tensors='pt', max_length=512, truncation=True)
        inputs = {k: v.to(device) for k, v in inputs.items()}
        
        # 获取嵌入
        with torch.no_grad():
            outputs = model(**inputs)
            embedding = outputs.last_hidden_state.mean(dim=1).cpu().numpy()
        
        # 情感分类
        sentiment = classify_sentiment(embedding)
        
        # 情感强度
        intensity = calculate_intensity(embedding)
        
        # 情感词提取
        sentiment_words = extract_sentiment_words(text)
        
        return {
            'success': True,
            'sentiment': {
                'label': sentiment,
                'intensity': intensity,
                'words': sentiment_words
            }
        }
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

def classify_sentiment(embedding):
    # 加载预训练的情感分类器
    classifier = load_sentiment_classifier()
    
    # 预测情感
    prediction = classifier.predict(embedding.reshape(1, -1))[0]
    
    # 映射到情感标签
    sentiment_map = {
        0: 'negative',
        1: 'neutral',
        2: 'positive'
    }
    
    return sentiment_map.get(prediction, 'neutral')

def calculate_intensity(embedding):
    # 计算情感强度
    intensity = np.linalg.norm(embedding)
    return float(intensity)

def extract_sentiment_words(text):
    # 加载情感词典
    sentiment_dict = load_sentiment_dictionary()
    
    # 提取情感词
    words = text.split()
    sentiment_words = []
    
    for word in words:
        if word in sentiment_dict:
            sentiment_words.append({
                'word': word,
                'polarity': sentiment_dict[word]['polarity'],
                'intensity': sentiment_dict[word]['intensity']
            })
    
    return sentiment_words

def load_sentiment_classifier():
    # 加载预训练的情感分类器
    # 这里使用简单的逻辑回归作为示例
    classifier = LogisticRegression()
    # 加载预训练权重...
    return classifier

def load_sentiment_dictionary():
    # 加载情感词典
    # 这里使用简单的词典作为示例
    dictionary = {
        '好': {'polarity': 'positive', 'intensity': 1.0},
        '坏': {'polarity': 'negative', 'intensity': 1.0},
        '喜欢': {'polarity': 'positive', 'intensity': 0.8},
        '讨厌': {'polarity': 'negative', 'intensity': 0.8},
        # 添加更多情感词...
    }
    return dictionary

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
    
    result = analyze_sentiment(text, model, tokenizer, device)
    print(json.dumps(result)) 