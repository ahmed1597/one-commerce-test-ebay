eBay Marketplace Inventory Sync
===============================

A **minimal, production-quality Laravel + Vue 3 application** that connects to eBay via OAuth, fetches inventory items, and syncs them on demand.Includes a clean frontend, API layer, Dockerized setup, and Playwright E2E tests.

This project is intentionally **focused and small** to demonstrate:

*   Clean architecture & separation of concerns
    
*   OAuth integration
    
*   API + frontend coordination
    
*   Deterministic local & Docker setup
    
*   Automated E2E testing
    

✨ Features
----------

*   eBay OAuth (Sandbox)
    
*   Connection status detection
    
*   Manual inventory sync
    
*   Vue 3 frontend (Vite)
    
*   Laravel 12 backend
    
*   MySQL persistence
    
*   Dockerized environment
    
*   Playwright E2E tests
    

Tech Stack
-------------

*   **Backend:** Laravel 12, PHP 8.4
    
*   **Frontend:** Vue 3, Vite
    
*   **Database:** MySQL 8
    
*   **Infra:** Docker & Docker Compose
    
*   **Testing:** Playwright (E2E)
    

Project Structure (High Level)
---------------------------------

Plain textANTLR4BashCC#CSSCoffeeScriptCMakeDartDjangoDockerEJSErlangGitGoGraphQLGroovyHTMLJavaJavaScriptJSONJSXKotlinLaTeXLessLuaMakefileMarkdownMATLABMarkupObjective-CPerlPHPPowerShell.propertiesProtocol BuffersPythonRRubySass (Sass)Sass (Scss)SchemeSQLShellSwiftSVGTSXTypeScriptWebAssemblyYAMLXML`   ├── app/  │   ├── Application/  │   ├── Domain/  │   └── Infrastructure/  ├── resources/  │   ├── js/  │   │   ├── app.js  │   │   └── InventoryPage.vue  │   └── views/  │       └── app.blade.php  ├── routes/  │   ├── web.php  │   └── api.php  ├── docker/  │   └── nginx/  ├── tests/  │   └── e2e/  ├── docker-compose.yml  └── README.md   `

Prerequisites
----------------

You only need:

*   Docker
    
*   Docker Compose
    

No local PHP, Node, or MySQL required.