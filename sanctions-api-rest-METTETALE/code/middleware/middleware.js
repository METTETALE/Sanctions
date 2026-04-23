import { verifyToken } from "./auth.js";

export function middleware(req, res, next) {
  // Autoriser les prérequis CORS
  if (req.method === "OPTIONS") return next();

  // Autoriser la route de login publique
  if (
    (req.path === "/api/auth/login" || req.path === "/auth/login") &&
    req.method === "POST"
  ) {
    return next();
  }

  // Pour toutes les autres routes, vérifier le token via la fonction utilitaire
  return verifyToken(req, res, next);
}

export default middleware;
