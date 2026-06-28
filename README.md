# 📚 README — Sistema de Gestão Escolar (SGE)
> Instituto Politécnico Privado Pitágoras de Samos

---

## 🧰 Tecnologias e Ferramentas Utilizadas

### Backend
| Tecnologia | Versão | Finalidade |
|---|---|---|
| **PHP** | 8.x | Linguagem principal do servidor |
| **Laravel** | 9/10 | Framework MVC do backend |
| **MySQL** | 8.x | Base de dados relacional |
| **Eloquent ORM** | (incluso no Laravel) | Comunicação com a base de dados |
| **Blade** | (incluso no Laravel) | Motor de templates para as views |
| **Middleware** | (incluso no Laravel) | Controlo de acesso por papel (role) |

### Frontend
| Tecnologia | Finalidade |
|---|---|
| **HTML5 / CSS3** | Estrutura e estilo das páginas |
| **Bootstrap 4** | Framework CSS responsivo |
| **SB Admin 2** | Template do painel administrativo |
| **jQuery 3.6** | Interatividade e chamadas AJAX |
| **DataTables** | Tabelas com pesquisa, paginação e exportação |
| **SweetAlert2** | Notificações e diálogos de confirmação elegantes |
| **Font Awesome** | Ícones do painel |
| **Google Fonts (Inter/Nunito)** | Tipografia moderna |
| **FullCalendar** | Calendário (na homepage do painel) |

### Pacotes Laravel (Composer)
| Pacote | Finalidade |
|---|---|
| `laravel/ui` | Scaffolding de autenticação (Login/Logout) |
| `barryvdh/laravel-dompdf` | Geração de PDFs (faturas, relatórios) |

---

## 🗂️ Estrutura do Projeto

```
schoolCris/
├── app/
│   ├── Http/
│   │   ├── Controllers/Admin/   → Todos os controladores do painel
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php        → Acesso: Admin e Secretaria
│   │       └── RegistradorMiddleware.php  → Regras de acesso da Secretaria
│   └── Models/                  → Modelos Eloquent (User, Role, Student, etc.)
│
├── database/
│   ├── migrations/              → Tabelas da base de dados
│   └── seeders/
│       ├── AdminSeeder.php      → Cria o utilizador admin padrão
│       └── RoleSeeder.php       → Cria todos os papéis (roles) do sistema
│
├── resources/views/
│   ├── admin/                   → Todas as views do painel
│   ├── layouts/
│   │   ├── merge/dashboard.blade.php      → Layout base do painel
│   │   └── _include/dashboard/
│   │       ├── aside-admin.blade.php      → Barra lateral (Admin + Secretaria)
│   │       ├── header.blade.php           → Cabeçalho HTML
│   │       ├── nav.blade.php              → Topbar e navbar
│   │       └── footer.blade.php           → Rodapé + todos os scripts JS
│   └── form/                    → Formulários reutilizáveis (ex: _formUser/)
│
├── routes/
│   └── Admin.php                → Todas as rotas do painel
│
└── public/dashboard/assets/
    ├── css/
    │   ├── custom-theme.css     ← 🎨 FICHEIRO PRINCIPAL PARA ALTERAR CORES
    │   ├── sb-admin-2.css       → CSS base do template
    │   ├── dark-mode.css        → Estilos do modo escuro
    │   └── custom-forms.css     → Estilos dos formulários
    └── scss/
        └── _variables.scss      ← SCSS: variáveis de cor (requer compilação)
```

---

## 🔑 Credenciais Padrão

| Papel | E-mail | Senha |
|---|---|---|
| Administrador | `admin@admin.com` | `123456` |
| Secretaria | `secretaria@escola.com` | `123456` |

---

#### 🔐 Sistema de Numeração de Alunos (nProcess)
O sistema utiliza agora um formato alfanumérico automático (**Ex: A1, B2, C10**):
- O número é gerado no momento da matrícula com base no **Curso + Classe**.
- Cada grupo (Curso/Classe) recebe uma letra única (A, B, C...).
- O número é sequencial dentro de cada letra.
- **Pesquisa**: No pagamento de propinas, basta digitar o número (ex: A1) para carregar os dados do aluno.

#### ✉️ Mensagens Internas (SMS do Sistema)
Permite a comunicação direta entre Admin e Secretaria:
- **Acesso**: Menu "Mensagens" na barra lateral.
- **Notificação**: Alertas em tempo real para mensagens novas (não lidas).
- **Histórico**: Pastas de Caixa de Entrada e Enviadas.

---

## 🎨 Personalização de Cores
A cor principal do painel pode ser alterada em `public/dashboard/assets/css/custom-theme.css`:

Localiza as primeiras linhas do ficheiro:

```css
:root {
    --blue: #FF8000;          /* Cor de destaque secundária */
    --primary: #FF8000;       /* ← COR PRINCIPAL (botões, sidebar, links) */
    --primary-darker: #E67300; /* ← COR MAIS ESCURA (hover dos botões) */
}
```

**Exemplo: mudar para azul**
```css
:root {
    --primary: #4e73df;
    --primary-darker: #2e59d9;
}
```

**Exemplo: mudar para verde**
```css
:root {
    --primary: #1cc88a;
    --primary-darker: #17a673;
}
```

> ✅ Ao alterar estas duas variáveis, **toda a interface** muda de cor: sidebar, botões, links, bordas, tabelas, hover, etc. — automaticamente.

---

### Cores por componente (referência rápida)

| Elemento | Propriedade CSS |
|---|---|
| Botões primários | `.btn-primary` → usa `--primary` |
| Barra lateral | `.bg-gradient-primary` → usa `--primary` |
| Links | `a` → usa `--primary` |
| Borda esquerda dos cards | `.border-left-primary` |
| Realce em tabelas | `.table-primary` |
| Inputs em foco | `.form-control:focus` |
| Notificações SweetAlert | (mantém azul por padrão — pode ser alterado) |

> **Nota:** O ficheiro `_variables.scss` contém as variáveis SCSS originais do template Bootstrap. Para as alterar precisas de recompilar o SCSS (requer Node.js + gulp). Para a maioria dos casos, alterar apenas o `custom-theme.css` é suficiente.

---

## 📦 Como Instalar o Projeto

```bash
# 1. Clonar ou copiar o projeto para a pasta desejada

# 2. Instalar dependências PHP
composer install

# 3. Copiar ficheiro de ambiente
cp .env.example .env

# 4. Gerar chave da aplicação
php artisan key:generate

# 5. Configurar a base de dados no .env
DB_DATABASE=nome_da_base
DB_USERNAME=root
DB_PASSWORD=

# 6. Executar migrações e seeders
php artisan migrate --seed

# 7. Criar link simbólico para storage (fotos)
php artisan storage:link

# 8. Iniciar o servidor local
php artisan serve
```

---

## 👥 Papéis (Roles) do Sistema

| Papel | Acesso |
|---|---|
| `admin` | Acesso total ao sistema |
| `Secretaria` | Acesso total exceto gerir utilizadores e professores |
| `Limpeza` | Apenas para identificação/listagem |
| `Seguranças` | Apenas para identificação/listagem |
| `Coordenadores` | Apenas para identificação/listagem |
| `Director Geral` | Apenas para identificação/listagem |
| `Supervisor da Biblioteca` | Apenas para identificação/listagem |
| `Chefe de patrimônio` | Apenas para identificação/listagem |
| `Electricista` | Apenas para identificação/listagem |
| `Técnico de Frio` | Apenas para identificação/listagem |
| `Técnico de CCTV` | Apenas para identificação/listagem |
| `Técnico IT` | Apenas para identificação/listagem |

> Os cargos administrativos são atribuídos pelo Admin ao criar um utilizador. Servem atualmente para identificação e registo no sistema.

---

## 🖨️ Geração de PDFs

O sistema usa o pacote **DomPDF** para gerar documentos em PDF:
- Faturas de propinas
- Faturas de transporte
- Listas de alunos matriculados
- Cartão do aluno
- Chefes de departamento

Os ficheiros de template PDF estão em `resources/views/pdf/`.
