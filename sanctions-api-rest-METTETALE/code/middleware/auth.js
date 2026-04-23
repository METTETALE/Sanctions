import jwt from "jsonwebtoken";

const SECRET_KEY = process.env.JWT_SECRET || "change_this_secret";

// Génère un token JWT pour un utilisateur
export function generateToken(user) {
  const now = Math.floor(Date.now() / 1000); // En secondes
  const expiresInEnv = Number(process.env.JWT_EXPIRES_IN);
  const exp = isNaN(expiresInEnv) ? now + 3600 : now + 3600 * expiresInEnv;

  const payload = {
    sub: user.id_user ? user.id_user.toString() : String(user.id || ""),
    iat: now,
    exp,
    id: user.id_user || user.id || null,
    email: user.email || null,
    nom: user.name || user.nom || null,
    prenom: user.surname || user.prenom || null,
  };

  return jwt.sign(payload, SECRET_KEY, { algorithm: "HS256" });
}

// Middleware utilitaire pour vérifier le token (peut être réutilisé)
export function verifyToken(req, res, next) {
  const authHeader = req.headers.authorization;

  if (!authHeader || !authHeader.startsWith("Bearer ")) {
    return res.status(401).json({ message: "Token manquant" });
  }

  const token = authHeader.substring(7);

  try {
    const decoded = jwt.verify(token, SECRET_KEY);

    req.user = {
      id: decoded.id || decoded.sub || null,
      email: decoded.email || null,
      nom: decoded.nom || null,
      prenom: decoded.prenom || null,
    };

    return next();
  } catch (error) {
    return res.status(401).json({ message: "Token invalide ou expiré" });
  }
}

export default generateToken;
