import { Command } from "commander";
import elevesCommand from "./commands/eleves.js";
import sanctionsCommand from "./commands/sanctions.js";
import logonCommand from "./commands/logon.js";
const program = new Command();

program
  .name("sanction-cli")
  .description("Application CLI pour utiliser L'API de l'Application sanction")
  .version("1.0.0");

// Ajouter des commandes ici

program
  .command("eleves [nom] [prenom]")
  .description("Recherche d'un élève par nom et ou prénom")
  .option("-n, --nom <nom>", "Nom de l'élève")
  .option("-p, --prenom <prenom>", "Prénom de l'élève")
  .action(elevesCommand);

program
  .command("sanctions <id>")
  .description("Lister les sanctions d'un élève donné par son ID")
  .option("-i, --id <id>", "ID de l'élève")
  .option("-f, --from <from>", "Date de début au format YYYY-MM-DD")
  .option("-t, --to <to>", "Date de fin au format YYYY-MM-DD")
  .action(sanctionsCommand);

program
  .command("login <username> <password>")
  .description("Se connecter et obtenir un token d'authentification")
  .action(logonCommand);

program.parse();
