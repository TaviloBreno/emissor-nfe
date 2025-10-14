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

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
