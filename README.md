# 📋 Emissor NFe - Sistema Completo de Nota Fiscal Eletrônica

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tests-45%20Passed-green?style=for-the-badge" alt="Tests">
</p>

<p align="center">
  Sistema profissional para emissão, gestão e controle de Notas Fiscais Eletrônicas (NFe) desenvolvido em Laravel.
</p>

---

## 🚀 **Funcionalidades Principais**

### 📄 **Gestão de Notas Fiscais**
- ✅ **Criação completa** de notas fiscais com validação
- ✅ **Geração de XML** estruturado e válido
- ✅ **Assinatura digital** simulada com certificado
- ✅ **Envio para SEFAZ** com retorno de protocolo
- ✅ **Controle de status** (rascunho → assinada → autorizada)

### 🔄 **Operações Pós-Emissão**
- ✅ **Cancelamento** com validação de prazo (24h)
- ✅ **Carta de Correção Eletrônica** (CCe) para ajustes
- ✅ **Inutilização de numeração** não utilizada
- ✅ **Manifestação do destinatário** (ciência, confirmação, discordância)

### 🛡️ **Segurança e Compliance**
- ✅ **Validações robustas** conforme legislação
- ✅ **Rastreabilidade completa** de eventos
- ✅ **Auditoria** de todas as operações
- ✅ **Protocolos únicos** e verificáveis

---

## 🏗️ **Arquitetura e Tecnologias**

### **Backend**
- **Framework**: Laravel 8.x
- **Linguagem**: PHP 7.4+
- **Banco de Dados**: MySQL
- **Testes**: PHPUnit com 45 testes e 190+ assertions

### **Estrutura de Services**
- **GeradorNFService**: Geração de XML
- **AssinadorService**: Assinatura digital
- **NFeClient**: Comunicação SEFAZ
- **NotaFiscalService**: Orquestração completa
- **CartaCorrecaoService**: Correções eletrônicas
- **InutilizacaoService**: Controle de numeração

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

---

## 📡 **API Endpoints**

### **Notas Fiscais**
```
GET    /notas                     - Listar notas fiscais
POST   /notas                     - Criar nova nota fiscal
GET    /notas/{id}               - Visualizar nota específica
PUT    /notas/{id}               - Atualizar nota fiscal
DELETE /notas/{id}               - Excluir nota fiscal
```

### **Operações Especiais**
```
POST   /notas/{id}/cancelar      - Cancelar nota fiscal
POST   /notas/{id}/correcao      - Emitir carta de correção
POST   /inutilizacao             - Inutilizar numeração
POST   /notas/{id}/manifestar    - Registrar manifestação do destinatário
```

---

## 🔧 **Instalação e Configuração**

### **Pré-requisitos**
- PHP 7.4+
- Composer
- MySQL 5.7+
- Laravel 8.x

### **Passo a Passo**

1. **Clone o repositório**
   ```bash
   git clone https://github.com/seu-usuario/emissor-nfe.git
   cd emissor-nfe
   ```

2. **Instale as dependências**
   ```bash
   composer install
   ```

3. **Configure o ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure o banco de dados**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=emissor_nfe
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

5. **Execute as migrações**
   ```bash
   php artisan migrate
   ```

6. **Execute os testes**
   ```bash
   php artisan test
   ```

---

## 📊 **Estrutura do Banco de Dados**

### **Tabela: nota_fiscals**
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | bigint | Identificador único |
| numero | int | Número sequencial da NF |
| serie | int | Série da nota fiscal |
| chave_acesso | varchar(44) | Chave única de acesso |
| destinatario_nome | varchar | Nome do destinatário |
| destinatario_cnpj | varchar(14) | CNPJ do destinatário |
| valor_total | decimal(10,2) | Valor total da nota |
| status | enum | Status atual da nota |
| protocolo_autorizacao | varchar | Protocolo da SEFAZ |
| xml_gerado | text | XML completo da NFe |

### **Tabela: eventos_nota_fiscal**
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | bigint | Identificador único |
| nota_fiscal_id | bigint | FK para nota fiscal |
| tipo_evento | enum | Tipo do evento |
| protocolo | varchar | Protocolo do evento |
| justificativa | text | Justificativa (se aplicável) |

---

## 🧪 **Testes**

O sistema possui **45 testes automatizados** cobrindo:

- ✅ **Testes de Feature**: Integração completa dos endpoints
- ✅ **Testes Unitários**: Services e validações isoladas
- ✅ **Cobertura completa**: Todas as funcionalidades testadas

### **Executar testes**
```bash
# Todos os testes
php artisan test

# Testes específicos
php artisan test tests/Feature/NotaFiscalTest.php
php artisan test tests/Unit/AssinadorServiceTest.php
```

---

## 📋 **Exemplos de Uso**

### **1. Criar Nova Nota Fiscal**
```json
POST /notas
{
    "numero": 1001,
    "serie": 1,
    "destinatario_nome": "Empresa XYZ Ltda",
    "destinatario_cnpj": "12345678000195",
    "valor_total": 1500.00,
    "itens": [
        {
            "descricao": "Produto A",
            "quantidade": 2,
            "valor_unitario": 750.00
        }
    ]
}
```

### **2. Cancelar Nota Fiscal**
```json
POST /notas/123/cancelar
{
    "justificativa": "Produto entregue com defeito"
}
```

### **3. Emitir Carta de Correção**
```json
POST /notas/123/correcao
{
    "correcao": "Correção do endereço de entrega",
    "condicoes_uso": "Erro na digitação do endereço"
}
```

### **4. Manifestação do Destinatário**
```json
POST /notas/123/manifestar
{
    "tipo_manifestacao": "confirmacao",
    "justificativa": "Mercadoria recebida conforme pedido"
}
```

---

## 🤝 **Contribuição**

1. Faça um Fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

---

## 📄 **Licença**

Este projeto está licenciado sob a [MIT License](https://opensource.org/licenses/MIT).

---

<p align="center">
  Desenvolvido com ❤️ usando Laravel | © 2024 Emissor NFe
</p>
