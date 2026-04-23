import pool from "../config/db.js";
import express from "express";
import bcrypt from "bcryptjs";
import generateToken from "../middleware/auth.js";

const router = express.Router();

// Get eleves
router.get("/eleves", async function (req, res) {
  try {
    const nom = String(req.query?.nom || "").trim();
    const prenom = String(req.query?.prenom || "").trim();

    let eleves = undefined;
    const baseQuery = `SELECT e.id, e.nom, e.prenom, e.date_naissance, c.id_classe, c.nom as classe_nom
      FROM eleves as e
      INNER JOIN classes as c ON e.id_classe = c.id_classe`;
    if (nom.length > 0 && prenom.length > 0) {
      [eleves] = await pool.query(
        `${baseQuery}
        WHERE LOWER(e.nom) LIKE LOWER(:nom) AND LOWER(e.prenom) LIKE LOWER(:prenom)`,
        {
          nom: nom.length > 0 ? `%${nom}%` : null,
          prenom: prenom.length > 0 ? `%${prenom}%` : null,
        },
      );
    } else if (nom.length > 0 && prenom.length === 0) {
      [eleves] = await pool.query(
        `${baseQuery}
            WHERE LOWER(e.nom) LIKE LOWER(:nom)`,
        {
          nom: nom.length > 0 ? `%${nom}%` : null,
        },
      );
    } else if (prenom.length > 0 && nom.length === 0) {
      [eleves] = await pool.query(
        `${baseQuery}
            WHERE LOWER(e.prenom) LIKE LOWER(:prenom)`,
        {
          prenom: prenom.length > 0 ? `%${prenom}%` : null,
        },
      );
    }
    if (eleves != undefined) {
      const elevesAvecClasses = eleves.map(
        ({ id_classe, classe_nom, ...rest }) => ({
          ...rest,
          date_naissance: new Date(rest.date_naissance)
            .toLocaleString("fr-FR")
            .slice(0, 10),
          classe: {
            id: id_classe,
            libelle: classe_nom ? `${classe_nom}` : null,
          },
        }),
      );
      res.json({ eleves: elevesAvecClasses });
    } else {
      res.status(400);
      res.json({ message: "Au moins un filtre est requis : nom et/ou prenom" });
    }
  } catch (error) {
    res.status(500);
    res.json({ message: "Erreur interne" });
  }
});

router.get("/eleves/:id/sanctions", async function (req, res) {
  try {
    const eleveId = parseInt(req.params.id, 10);
    let from = req.query?.from;
    let to = req.query?.to;

    if (from) {
      from = new Date(from);
      if (isNaN(from.getTime())) {
        res.status(400);
        res.json({
          message:
            "Format de date invalide. Utilisez YYYY/MM/DD pour 'from' et 'to'.",
        });
        return;
      }
    }

    if (to) {
      to = new Date(to);
      if (isNaN(to.getTime())) {
        res.status(400);
        res.json({
          message:
            "Format de date invalide. Utilisez YYYY/MM/DD pour 'from' et 'to'.",
        });
        return;
      }
    }

    if (!eleveId) {
      res.status(400);
      res.json({ message: "Élève introuvable" });
      return;
    }

    if (!from && to) {
      res.status(400);
      res.json({
        message:
          "Le filtre 'to' ne peut pas être utilisé seul. Utilisez 'from' ou 'from' + 'to'.",
      });
      return;
    }

    if (from && !to) {
      to = new Date();
    }

    if (to < from) {
      res.status(400);
      res.json({
        message:
          "Période invalide : 'from' doit être antérieur ou égal à 'to'.",
      });
      return;
    }

    const [[eleve]] = await pool.query(
      `SELECT e.id, e.nom, e.prenom, e.date_naissance, c.id_classe, c.nom as classe_nom
      FROM eleves as e
      INNER JOIN classes as c ON e.id_classe = c.id_classe
      WHERE e.id = :eleveId`,
      { eleveId },
    );

    if (!eleve) {
      res.status(404);
      res.json({ message: "Élève introuvable" });
      return;
    }

    const eleveFormate = {
      id: eleve.id,
      nom: eleve.nom,
      prenom: eleve.prenom,
      date_naissance: eleve.date_naissance.toLocaleString("fr-FR").slice(0, 10),
      classe: {
        id: eleve.id_classe,
        libelle: eleve.classe_nom,
      },
    };
    let sanctions = [];
    if (!from && !to) {
      [sanctions] = await pool.query(
        `SELECT s.id, s.type, s.date, s.motif, p.id as id_auteur, p.nom as nom_auteur, p.prenom as prenom_auteur
      FROM sanctions AS s INNER JOIN professeurs AS p ON s.id_professeur = p.id
      WHERE s.id_eleve = :eleveId`,
        { eleveId },
      );
    } else {
      [sanctions] = await pool.query(
        `SELECT s.id, s.type, s.date, s.motif, p.id as id_auteur, p.nom as nom_auteur, p.prenom as prenom_auteur
      FROM sanctions AS s INNER JOIN professeurs AS p ON s.id_professeur = p.id
      WHERE s.id_eleve = :eleveId
      AND s.date >= :from
      AND s.date <= :to`,
        { eleveId, from, to },
      );
    }
    const sanctionsFormatees = sanctions.map((sanction) => ({
      id: sanction.id,
      type: sanction.type,
      motif: sanction.motif,
      date: new Date(sanction.date).toLocaleString("fr-FR").slice(0, 10),
      auteur: {
        id: sanction.id_auteur,
        nom: sanction.nom_auteur,
        prenom: sanction.prenom_auteur,
      },
    }));
    res.json({ eleve: eleveFormate, sanctions: sanctionsFormatees });
    return;
  } catch (error) {
    res.status(500);
    res.json({ message: "Erreur interne" + error.message });
  }
});

router.post("/auth/login", async function (req, res) {
  try {
    if (!req.body.email || !req.body.password) {
      res.status(400);
      res.json({ message: "Email et mot de passe requis" });
      return;
    }
    const email = String(req.body.email).trim();
    const password = String(req.body.password).trim();

    const [[password_hashed]] = await pool.query(
      `SELECT password_hash
      FROM users
      WHERE email = :email`,
      { email },
    );

    if (
      !password_hashed ||
      !bcrypt.compareSync(password, password_hashed.password_hash)
    ) {
      res.status(401);
      res.json({ message: "Email ou mot de passe incorrect" });
      return;
    }
    const [[user]] = await pool.query(
      `SELECT id_user, email, name, surname
      FROM users
      WHERE email = :email`,
      { email },
    );
    const token = generateToken(user);
    res.json({ token });
  } catch (error) {
    res.status(500);
    res.json({ message: "Erreur interne " + error });
  }
});

export default router;
