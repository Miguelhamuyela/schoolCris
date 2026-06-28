# Diagrama Entidade-Relacionamento — SchoolManagement

> **Nota:** Nomes em português (após migrações de renomeação).

---

## 1. NÚCLEO ACADÉMICO

```mermaid
erDiagram
    %% ==================== ENTIDADES PRINCIPAIS ====================
    alunos {
        bigint id PK
        string name
        string nProcess
        string nBi UK
        string father
        string mother
        string contact
        string contactAlter
        string email
        string image "nullable"
        date dateBirth
        string schoolyear
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    docentes {
        bigint id PK
        string nBi
        string name
        string email UK
        string contact
        string contactAlter
        string literary
        string specialty
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    usuarios {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at "nullable"
        string password
        remember_token
        string image "nullable"
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    cursos {
        bigint id PK
        string name
        string details
        string duration
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    niveis {
        bigint id PK
        string name
        string details
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    turmas {
        bigint id PK
        string name
        string details
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    anos_lectivos {
        bigint id PK
        string name
        string details
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }
```

```mermaid
erDiagram
    %% ==================== MATRÍCULAS & CONTRATOS ====================
    matriculas {
        bigint id PK
        string season
        bigint fk_courses_id FK
        bigint fk_classes_id FK
        bigint fk_grades_id FK
        bigint fk_students_id FK
        bigint fk_schoolyears_id FK
        string details
        string status "default Pendente"
        boolean is_confirmation "default false"
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    contratos {
        bigint id PK
        string season
        bigint fk_teachers_id FK
        bigint fk_classes_id FK "nullable"
        bigint fk_rules_id FK "nullable"
        bigint fk_schoolyears_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }
```

```mermaid
erDiagram
    %% ==================== RELACIONAMENTOS NÚCLEO ====================
    alunos ||--o{ matriculas : "1:N"
    cursos ||--o{ matriculas : "1:N"
    turmas ||--o{ matriculas : "1:N"
    niveis ||--o{ matriculas : "1:N"
    anos_lectivos ||--o{ matriculas : "1:N"

    docentes ||--o{ contratos : "1:N"
    turmas ||--o{ contratos : "1:N"
    regras ||--o{ contratos : "1:N"
    anos_lectivos ||--o{ contratos : "1:N"
```

---

## 2. EXAMES, GRUPOS & DISCIPLINAS

```mermaid
erDiagram
    exames {
        bigint id PK
        string name
        string season
        string duration
        datetime start
        datetime end
        bigint fk_teachers_id FK
        bigint fk_classes_id FK
        bigint fk_courses_id FK
        bigint fk_grades_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    grupos_cursos {
        bigint id PK
        bigint fk_courses_id FK
        bigint fk_grades_id FK
        string letter
        timestamp created_at
        timestamp updated_at
        %% unique: (fk_courses_id, fk_grades_id)
    }

    cursos_disciplinas_niveis {
        bigint id PK
        bigint fk_courses_id FK
        bigint fk_grades_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }
```

```mermaid
erDiagram
    docentes ||--o{ exames : "1:N"
    turmas   ||--o{ exames : "1:N"
    cursos   ||--o{ exames : "1:N"
    niveis   ||--o{ exames : "1:N"

    cursos ||--o{ grupos_cursos : "1:N"
    niveis ||--o{ grupos_cursos : "1:N"

    cursos ||--o{ cursos_disciplinas_niveis : "1:N"
    niveis ||--o{ cursos_disciplinas_niveis : "1:N"
```

---

## 3. PAGAMENTOS

```mermaid
erDiagram
    propinas {
        bigint id PK
        string month
        bigint price
        decimal fine "default 0"
        string state
        bigint fk_students_id FK
        bigint fk_schoolyears_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    pagamentos_transportes {
        bigint id PK
        string month
        bigint price
        string state
        bigint fk_students_id FK
        bigint fk_schoolyears_id FK
        bigint fk_transports_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    pagamentos_uniformes {
        bigint id PK
        bigint price
        string state
        bigint fk_students_id FK
        bigint fk_schoolyears_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    transportes {
        bigint id PK
        string plate
        string model
        integer capacity
        string route
        string state
        string safe
        string documentation
        string details
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }
```

```mermaid
erDiagram
    alunos ||--o{ propinas : "1:N"
    anos_lectivos ||--o{ propinas : "1:N"

    alunos ||--o{ pagamentos_transportes : "1:N"
    anos_lectivos ||--o{ pagamentos_transportes : "1:N"
    transportes ||--o{ pagamentos_transportes : "1:N"

    alunos ||--o{ pagamentos_uniformes : "1:N"
    anos_lectivos ||--o{ pagamentos_uniformes : "1:N"
```

---

## 4. SEGURANÇA & COMUNICAÇÃO

```mermaid
erDiagram
    papeis {
        bigint id PK
        string name
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    usuarios_papeis {
        bigint id PK
        bigint fk_users_id FK
        bigint fk_roles_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    mensagens {
        bigint id PK
        bigint sender_id FK "-> usuarios"
        bigint receiver_id FK "-> usuarios"
        string subject
        text body
        boolean read "default false"
        timestamp read_at "nullable"
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    regras {
        bigint id PK
        string name
        string details
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }
```

```mermaid
erDiagram
    usuarios ||--o{ usuarios_papeis : "1:N"
    papeis  ||--o{ usuarios_papeis : "1:N"

    usuarios ||--o{ mensagens : "sender_id"
    usuarios ||--o{ mensagens : "receiver_id"
```

---

## 5. PIVOTS — DOCENTES

```mermaid
erDiagram
    docentes_anos_lectivos {
        bigint id PK
        bigint fk_teachers_id FK
        bigint fk_schoolyears_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    docentes_regras {
        bigint id PK
        bigint fk_teachers_id FK
        bigint fk_rules_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    docentes_cursos {
        bigint id PK
        bigint fk_teachers_id FK
        bigint fk_courses_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    docentes_turmas {
        bigint id PK
        bigint fk_teachers_id FK
        bigint fk_classes_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }

    docentes_niveis {
        bigint id PK
        bigint fk_teachers_id FK
        bigint fk_grades_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }
```

```mermaid
erDiagram
    docentes    ||--o{ docentes_anos_lectivos : "1:N"
    anos_lectivos ||--o{ docentes_anos_lectivos : "1:N"

    docentes ||--o{ docentes_regras : "1:N"
    regras   ||--o{ docentes_regras : "1:N"

    docentes ||--o{ docentes_cursos : "1:N"
    cursos   ||--o{ docentes_cursos : "1:N"

    docentes ||--o{ docentes_turmas : "1:N"
    turmas   ||--o{ docentes_turmas : "1:N"

    docentes ||--o{ docentes_niveis : "1:N"
    niveis   ||--o{ docentes_niveis : "1:N"
```

---

## 6. SISTEMA (Laravel / Logs)

```mermaid
erDiagram
    sessions {
        string id PK
        bigint user_id FK "nullable, index"
        string ip_address "nullable"
        text user_agent "nullable"
        longtext payload
        integer last_activity "index"
    }

    password_resets {
        string email "index"
        string token
        timestamp created_at
    }

    failed_jobs {
        bigint id PK
        string uuid UK
        text connection
        text queue
        longtext payload
        longtext exception
        timestamp failed_at
    }

    personal_access_tokens {
        bigint id PK
        string tokenable_type
        bigint tokenable_id
        string name
        string token UK
        text abilities "nullable"
        timestamp last_used_at "nullable"
        timestamp expires_at "nullable"
        timestamp created_at
        timestamp updated_at
    }

    registos {
        bigint id PK
        enum level "emergency...debug"
        string REMOTE_ADDR "nullable"
        string PATH_INFO "nullable"
        string USER_NAME "nullable"
        string USER_ID "nullable"
        string HTTP_USER_AGENT "nullable"
        longtext message "nullable"
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "softDeletes"
    }
```

---

## MAPA COMPLETO DE RELACIONAMENTOS

```
┌────────────────────────────────────────────────────────────────────┐
│                        DIAGRAMA CONCEITUAL                        │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│  ┌──────────┐    ┌──────────────┐    ┌────────────────────┐      │
│  │ alunos   │───▷│  matriculas  │◁───│ cursos             │      │
│  └──────────┘    │              │    └────────────────────┘      │
│                  │  (matricula  │    ┌────────────────────┐      │
│  ┌──────────┐    │   N:N)      │◁───│ turmas             │      │
│  │ docentes │───▷│              │    └────────────────────┘      │
│  └──────────┘    └──────────────┘    ┌────────────────────┐      │
│       │                             │ niveis             │      │
│       │         ┌──────────────┐    └────────────────────┘      │
│       ├────────▷│  contratos   │◁─── anos_lectivos              │
│       │         └──────────────┘                                │
│       │         ┌──────────────┐    ┌────────────────────┐      │
│       ├────────▷│   exames     │◁───│ cursos, turmas,    │      │
│       │         └──────────────┘    │ niveis             │      │
│       │                             └────────────────────┘      │
│       ├──────────┐  ┌──────────────────────────────────┐        │
│       │          ├─▷│ docentes_anos_lectivos (pivot)   │        │
│       │          ├─▷│ docentes_regras (pivot)          │        │
│       │          ├─▷│ docentes_cursos (pivot)          │        │
│       │          ├─▷│ docentes_turmas (pivot)          │        │
│       │          └─▷│ docentes_niveis (pivot)          │        │
│       │             └──────────────────────────────────┘        │
│       │                                                         │
│       └──────────┐                                              │
│                  ▼                                              │
│  ┌──────────────────────────────────────────────────────┐       │
│  │           PAGAMENTOS (por aluno + ano lectivo)       │       │
│  │  ┌──────────┐  ┌──────────────────┐  ┌────────────┐ │       │
│  │  │ propinas │  │pagamentos_transp │  │pagt_uniformes│ │       │
│  │  └──────────┘  └──────────────────┘  └────────────┘ │       │
│  └──────────────────────────────────────────────────────┘       │
│                                                                    │
│  ┌─────────────────────────────────────────────┐                 │
│  │  USUÁRIOS                                   │                 │
│  │  usuarios──usuarios_papeis──papeis          │                 │
│  │  usuarios──mensagens (sender/receiver)      │                 │
│  └─────────────────────────────────────────────┘                 │
│                                                                    │
│  ┌─────────────────────────────────────────────┐                 │
│  │  SISTEMA: sessions, password_resets,       │                 │
│  │            failed_jobs, personal_access_    │                 │
│  │            tokens, registos                 │                 │
│  └─────────────────────────────────────────────┘                 │
└────────────────────────────────────────────────────────────────────┘
```

---

## TABELAS REMOVIDAS (histórico)

| Nome Original | Nome PT | Motivo |
|--------------|---------|--------|
| `bedrooms` | `quartos` | Drop |
| `bedroom_students` | `alunos_quartos` | Drop |
| `residences` | `residencias` | Drop |
| `professores` | — | Drop (tabela duplicada) |
| `departments` | `departamentos` | Drop |
| `subjects` | `disciplinas` | Drop (e colunas FK removidas) |
| `teachers_subjects` | `docentes_disciplinas` | Drop |
