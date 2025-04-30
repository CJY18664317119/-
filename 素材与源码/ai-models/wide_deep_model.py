import tensorflow as tf
from tensorflow.keras.layers import Dense, Input, Embedding, Concatenate, Flatten
from tensorflow.keras.models import Model
import numpy as np

class WideDeepModel:
    def __init__(self, user_feature_columns, item_feature_columns):
        self.user_feature_columns = user_feature_columns
        self.item_feature_columns = item_feature_columns
        self.model = self._build_model()
        
    def _build_model(self):
        # Wide部分
        wide_input = Input(shape=(len(self.user_feature_columns) + len(self.item_feature_columns),))
        wide_output = Dense(1, activation='linear')(wide_input)
        
        # Deep部分
        deep_input = Input(shape=(len(self.user_feature_columns) + len(self.item_feature_columns),))
        deep_output = Dense(256, activation='relu')(deep_input)
        deep_output = Dense(128, activation='relu')(deep_output)
        deep_output = Dense(64, activation='relu')(deep_output)
        deep_output = Dense(1, activation='linear')(deep_output)
        
        # 合并Wide和Deep
        merged = Concatenate()([wide_output, deep_output])
        output = Dense(1, activation='sigmoid')(merged)
        
        model = Model(inputs=[wide_input, deep_input], outputs=output)
        model.compile(optimizer='adam', loss='binary_crossentropy', metrics=['accuracy'])
        
        return model
    
    def train(self, X_wide, X_deep, y, epochs=10, batch_size=32):
        self.model.fit(
            [X_wide, X_deep],
            y,
            epochs=epochs,
            batch_size=batch_size,
            validation_split=0.2
        )
    
    def predict(self, X_wide, X_deep):
        return self.model.predict([X_wide, X_deep])
    
    def save_model(self, path):
        self.model.save(path)
    
    @classmethod
    def load_model(cls, path):
        model = tf.keras.models.load_model(path)
        return model

# 示例使用
if __name__ == "__main__":
    # 用户特征列
    user_features = [
        'teaching_style',  # 教学风格：传统、互动、探究等
        'subject',         # 学科：数学、语文、英语等
        'grade_level',     # 年级：小学、初中、高中
        'experience',      # 教学经验：年数
        'qualification',   # 资质：教师资格证等级
        'preferred_method',# 偏好的教学方法
        'class_size',      # 班级规模
        'tech_comfort',    # 技术使用舒适度
        'student_level',   # 学生水平：基础、中等、优秀
        'time_constraint'  # 时间限制
    ]
    
    # 教学资源特征列
    item_features = [
        'strategy_type',   # 策略类型：讲授、讨论、实验等
        'difficulty',      # 难度级别
        'time_required',   # 所需时间
        'resource_type',   # 资源类型：课件、视频、练习等
        'interactivity',   # 互动性
        'tech_requirement',# 技术要求
        'adaptability',    # 适应性
        'assessment_type', # 评估类型
        'learning_style',  # 学习风格
        'prerequisites'    # 先决条件
    ]
    
    # 创建模型
    model = WideDeepModel(user_features, item_features)
    
    # 生成示例数据
    num_samples = 1000
    X_wide = np.random.rand(num_samples, len(user_features) + len(item_features))
    X_deep = np.random.rand(num_samples, len(user_features) + len(item_features))
    y = np.random.randint(0, 2, size=(num_samples, 1))
    
    # 训练模型
    model.train(X_wide, X_deep, y, epochs=20, batch_size=64)
    
    # 保存模型
    model.save_model('wide_deep_model.h5')
    
    # 示例预测
    test_X_wide = np.random.rand(10, len(user_features) + len(item_features))
    test_X_deep = np.random.rand(10, len(user_features) + len(item_features))
    predictions = model.predict(test_X_wide, test_X_deep)
    print("Predictions:", predictions) 