const BaseUrl = "http://localhost:4500/api/eleves";
import getTokenFromFile from "./verifyToken.js";

async function elevesCommand(nom, prenom, options) {
  if (!getTokenFromFile()) {
    console.error(
      "Token d'authentification manquant. Veuillez vous connecter d'abord.",
    );
    return;
  }
  const Optnom = nom || options.nom;
  const Optprenom = prenom || options.prenom;
  try {
    let res = await fetch(
      `${BaseUrl}?nom=${Optnom || ""}&prenom=${Optprenom || ""}`,
      {
        headers: {
          Authorization: `Bearer ${getTokenFromFile()}`,
        },
      },
    );
    let data = await res.json();
    if (!res.ok) {
      console.error(`Erreur : ${res.status} - ${data.message}`);
      return;
    }
    data.eleves.forEach((eleve) => {
      console.log(
        `ID: ${eleve.id}, Nom: ${eleve.nom}, Prénom: ${eleve.prenom}, \nClasse: { Id: ${eleve.classe.id}, Libellé: ${eleve.classe.libelle} }\n`,
      );
    });
  } catch (error) {
    console.error(
      `Erreur lors de la récupération des élèves ${Optnom || ""} ${Optprenom || ""} :\n`,
      error,
    );
  }
}

export default elevesCommand;
