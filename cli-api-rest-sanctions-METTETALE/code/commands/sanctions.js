const BaseUrl = "http://localhost:4500/api/eleves";
import getTokenFromFile from "./verifyToken.js";

async function sanctionsCommand(id, options) {
  if (!getTokenFromFile()) {
    console.error(
      "Token d'authentification manquant. Veuillez vous connecter d'abord.",
    );
    return;
  }
  const Optid = id || options.id;
  const Optfrom = options.from;
  const Optto = options.to;
  let url = `${BaseUrl}/${Optid}/sanctions`;
  if (Optfrom) {
    url += `?from=${Optfrom}`;
    if (Optto) {
      url += `&to=${Optto}`;
    }
  } else if (Optto) {
    url += `?to=${Optto}`;
  }
  try {
    let res = await fetch(url, {
      headers: {
        Authorization: `Bearer ${getTokenFromFile()}`,
      },
    });
    let data = await res.json();
    if (!res.ok) {
      console.error(`Erreur : ${res.status} - ${data.message}`);
      return;
    }
    console.log("\nélève :");
    console.log(
      `ID: ${data.eleve.id}, Nom: ${data.eleve.nom}, Prénom: ${data.eleve.prenom}, Classe: { Id: ${data.eleve.classe.id}, Libellé: ${data.eleve.classe.libelle} }\n`,
    );
    if (data.sanctions.length === 0) {
      if (Optfrom || Optto) {
        console.log(
          "Aucune sanction trouvée pour cet élève dans la plage de dates spécifiée.\n",
        );
      } else {
        console.log("Aucune sanction trouvée pour cet élève.\n");
      }
    } else {
      console.log("Sanctions de l'élève :");
      data.sanctions.forEach((sanction) => {
        console.log(
          `ID: ${sanction.id}, Type: ${sanction.type}, Date: ${sanction.date}, Motif: ${sanction.motif}`,
        );
        console.log("Auteur: ");
        console.log(
          `ID:${sanction.auteur.id}, Nom: ${sanction.auteur.nom}, Prénom: ${sanction.auteur.prenom}\n`,
        );
      });
    }
  } catch (error) {
    console.error(
      `Erreur lors de la récupération des sanctions de l'élève ${Optid} :\n`,
      error,
    );
  }
}

export default sanctionsCommand;
