```mermaid
stateDiagram-v2
  [*] --> Unapplied
  Unapplied --> Applying
  Applying --> Applied
  Applied --> Unapplied: Reverted
  
```
