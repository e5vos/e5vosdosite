# API Endpoint Proposal Template

## Summary

Include a short summary of the proposal here. Describe where the endpoint will be used, what it will do, and why it is needed.

## Endpoint Details

```yaml
/events/:
  description: Returns all events
  get:
    summary: Get Event List
    tags:
      - event
    responses:
      "200":
        description: Event list
        content:
          application/json:
            schema:
              type: array
              items:
                $ref: "#/components/schemas/Event"
  post:
    summary: Create event
    tags:
      - event
    responses:
      "201":
        description: Event created
        content:
          application/json:
            schema:
              $ref: "#/components/schemas/Event"
    security:
      - e5vosdo_auth:
          - write:events
```

## Model Modifications

Include any changes to the models here. Always include the full model according to your proposal, as well as the diff.

```diff
ends_at:
    type: string
-   nullable: true
+   nullable: false
```

```yaml
Event:
  type: object
  properties:
    id:
      type: integer
      format: int64
      example: 10
    name:
      type: string
      example: StarCraft Verseny
    stub:
      type: string
      example: starcraft_verseny
    description:
      type: string
      nullable: true
    capacity:
      type: string
      format: int64
      nullable: true
    signup_type:
      type: string
      enum: ["individual", "team"]
      nullable: true
    signup_deadline:
      type: string
      nullable: true
    starts_at:
      type: string
      nullable: true
    ends_at:
      type: string
      nullable: false
```
