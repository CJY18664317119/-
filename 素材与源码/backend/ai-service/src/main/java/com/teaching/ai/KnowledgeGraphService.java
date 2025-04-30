package com.teaching.ai;

import org.springframework.stereotype.Service;
import org.springframework.data.neo4j.core.Neo4jTemplate;
import org.springframework.data.neo4j.core.schema.Node;
import org.springframework.data.neo4j.core.schema.Relationship;
import org.springframework.data.neo4j.core.schema.Id;
import org.springframework.data.neo4j.core.schema.Property;
import org.springframework.data.neo4j.core.Neo4jClient;
import java.util.Map;
import java.util.HashMap;
import java.util.List;

@Service
public class KnowledgeGraphService {
    private final Neo4jTemplate neo4jTemplate;
    private final Neo4jClient neo4jClient;
    
    public KnowledgeGraphService(Neo4jTemplate neo4jTemplate, Neo4jClient neo4jClient) {
        this.neo4jTemplate = neo4jTemplate;
        this.neo4jClient = neo4jClient;
    }
    
    @Node("Concept")
    public static class Concept {
        @Id
        private String id;
        
        @Property("name")
        private String name;
        
        @Property("props")
        private Map<String, Object> props;
        
        public Concept(String id, String name, Map<String, Object> props) {
            this.id = id;
            this.name = name;
            this.props = props;
        }
        
        // Getters and setters
        public String getId() { return id; }
        public String getName() { return name; }
        public Map<String, Object> getProps() { return props; }
    }
    
    public static class ConceptRelationship {
        @Relationship(type = "REQUIRES")
        private String type;
        
        private Concept source;
        private Concept target;
        
        public ConceptRelationship(String type, Concept source, Concept target) {
            this.type = type;
            this.source = source;
            this.target = target;
        }
        
        // Getters
        public String getType() { return type; }
        public Concept getSource() { return source; }
        public Concept getTarget() { return target; }
    }
    
    public void createConcept(String id, String name, Map<String, Object> props) {
        Concept concept = new Concept(id, name, props);
        neo4jTemplate.save(concept);
    }
    
    public void createRelationship(String sourceId, String targetId, String type) {
        String cypher = "MATCH (a:Concept), (b:Concept) " +
                       "WHERE a.id = $sourceId AND b.id = $targetId " +
                       "CREATE (a)-[r:REQUIRES {type: $type}]->(b)";
        
        Map<String, Object> parameters = new HashMap<>();
        parameters.put("sourceId", sourceId);
        parameters.put("targetId", targetId);
        parameters.put("type", type);
        
        neo4jClient.query(cypher).bindAll(parameters).run();
    }
    
    public List<Concept> findRelatedConcepts(String conceptId, int depth) {
        String cypher = "MATCH path = (c:Concept {id: $id})-[*1.." + depth + "]-(related:Concept) " +
                       "RETURN DISTINCT related";
        
        Map<String, Object> parameters = new HashMap<>();
        parameters.put("id", conceptId);
        
        return neo4jTemplate.findAll(cypher, parameters, Concept.class);
    }
    
    public void deleteConcept(String conceptId) {
        String cypher = "MATCH (c:Concept {id: $id}) DETACH DELETE c";
        Map<String, Object> parameters = new HashMap<>();
        parameters.put("id", conceptId);
        
        neo4jClient.query(cypher).bindAll(parameters).run();
    }
    
    public void updateConceptProps(String conceptId, Map<String, Object> newProps) {
        String cypher = "MATCH (c:Concept {id: $id}) SET c.props = $props";
        Map<String, Object> parameters = new HashMap<>();
        parameters.put("id", conceptId);
        parameters.put("props", newProps);
        
        neo4jClient.query(cypher).bindAll(parameters).run();
    }
} 