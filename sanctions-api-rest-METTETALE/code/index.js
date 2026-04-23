import eleves from "./routes/sanctions-db-routes.js";
import "dotenv/config";
import express from "express";
import bodyParser from "body-parser";

const app = express(),
  PORT = process.env.PORT;
app.use(bodyParser.json());

import middleware from "./middleware/middleware.js";
app.use(middleware);

app.use("/api", eleves);
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}.`);
});
