package com.teaching.collab;

import org.springframework.stereotype.Service;
import org.springframework.web.socket.WebSocketHandler;
import com.fasterxml.jackson.databind.JsonNode;
import java.util.List;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.CopyOnWriteArrayList;
import java.util.Comparator;

@Service
public class CollaborationService {
    private final ConcurrentHashMap<String, List<Operation>> documentStates = new ConcurrentHashMap<>();
    
    public static class Operation {
        private String userId;
        private long timestamp;
        private String type;
        private JsonNode content;
        
        public Operation(String userId, long timestamp, String type, JsonNode content) {
            this.userId = userId;
            this.timestamp = timestamp;
            this.type = type;
            this.content = content;
        }
        
        // Getters and setters
        public String getUserId() { return userId; }
        public long getTimestamp() { return timestamp; }
        public String getType() { return type; }
        public JsonNode getContent() { return content; }
    }
    
    public void handleOperation(String docId, Operation op) {
        List<Operation> operations = documentStates.computeIfAbsent(docId, k -> new CopyOnWriteArrayList<>());
        operations.add(op);
        
        // CRDT合并逻辑
        Operation mergedOp = mergeCRDT(operations);
        
        // 广播到所有用户
        broadcastOperation(docId, mergedOp);
    }
    
    private Operation mergeCRDT(List<Operation> operations) {
        // 实现CRDT合并算法
        // 1. 按时间戳排序
        operations.sort(Comparator.comparingLong(Operation::getTimestamp));
        
        // 2. 合并操作
        Operation mergedOp = operations.get(0);
        for (int i = 1; i < operations.size(); i++) {
            mergedOp = mergeTwoOperations(mergedOp, operations.get(i));
        }
        
        return mergedOp;
    }
    
    private Operation mergeTwoOperations(Operation op1, Operation op2) {
        // 实现两个操作的合并逻辑
        // 这里使用简单的基于时间戳的合并策略
        if (op1.getTimestamp() > op2.getTimestamp()) {
            return op1;
        } else {
            return op2;
        }
    }
    
    private void broadcastOperation(String docId, Operation op) {
        // 实现WebSocket广播逻辑
        // 这里需要注入WebSocketHandler
    }
} 