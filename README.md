# Laravel Application

## Přehled

Tento projekt je aplikace vyvinutá v rámci frameworku Laravel. Tento soubor README obsahuje informace o instalaci, konfiguraci a spuštění aplikace.

## Požadavky

Před instalací aplikace se ujistěte, že máte nainstalovány následující nástroje:

- [PHP](https://www.php.net/) (verze 8.0 nebo vyšší)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/) (nebo jiná databáze, kterou používáte)

## Instalace

1. **Klonování repozitáře**

   Nejprve si klonujte repozitář:

   ```bash
   git clone https://github.com/postavstrom/taktik-laravel.git
   ```

   Přejděte do adresáře s projektem:

   ```bash
   cd taktik-laravel
   ```

2. **Instalace závislostí**

   Instalujte PHP závislosti pomocí Composeru:

   ```bash
   composer install
   ```

3. **Nastavení prostředí**

   Zkopírujte konfigurační soubor .env.example do nového souboru .env:

   ```bash
   cp .env.example .env
   ```

   Otevřete soubor .env a nakonfigurujte jej podle vašich potřeb. Ujistěte se, že máte správně nastavené připojení k databázi.

4. **Migrace databáze**

   Proveďte migrace pro vytvoření potřebných tabulek:

   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Spuštění aplikace**

   Spusťte PHP server:

   ```bash
   php artisan serve
   ```

6. **Testování aplikace**

   Aplikace obsahuje testy, které můžete spustit pomocí PHPUnit. Pro spuštění testů použijte:

   ```bash
   php artisan test
   ```

