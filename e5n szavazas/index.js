//Függvénykönyvtárak importálása
const { JsonDB } = require('node-json-db');
const { Config } = require('node-json-db/dist/lib/JsonDBConfig')

const fs = require('fs');

const express = require('express');
const rateLimit = require("express-rate-limit");
const app = express();
const http = require('http');
const server = http.createServer(app);
app.use(express.static('views'))
app.set("view engine", "ejs");
app.use(require('body-parser').urlencoded({ extended: false }));

const { OAuth2Client } = require('google-auth-library');
const client = new OAuth2Client('34242585475-n7rb0iodm7m9lphe60knplli29gfbj5t.apps.googleusercontent.com');

var votes = new JsonDB(new Config("./db/votes", true, false, '/'));
var voted = new JsonDB(new Config("./db/voted", true, false, '/'))


const szavazas = rateLimit({
  windowMs: 1 * 60 * 1000, 
  max: 20,
  message:
    "Túl sok kérés, próbáld újra később!"
});

const main = rateLimit({
  windowMs: 1 * 60 * 1000, 
  max: 30,
  message:
    "Túl sok kérés, próbáld újra később!"
});

//A használt id
function use(search) {
  var data = voted.getData("/tokens")
  for (var i = 0; i < data.length; i++) {
    if (data[i].token == search) {
      return true;
    }
  }
}


//Token hitelesítése 
async function verify(token) {
  const ticket = await client.verifyIdToken({
    idToken: token,
    audience: '34242585475-n7rb0iodm7m9lphe60knplli29gfbj5t.apps.googleusercontent.com',
  });
  const payload = ticket.getPayload();
  const userid = payload['sub'];
  return ticket
}

//Express útvonalak
//Főoldal lekérése
app.get("/", main, function(req, res) {
  var json = votes.getData("/")
  res.render("index.ejs", { data: json });
});


//Szavazási kérés és lezelése

app.post('/vote', szavazas, async function(req, res) {
  var ver = await verify(req.body.token)
  if (ver.payload.email_verified == true) {//A JWT Tokenből olvassa ki az emailt

    if (ver.payload.email.substr(ver.payload.email.length - 9) == '@e5vos.hu') {

      var dbchk = use(req.body.id)  //A token egyszeri használatának ellenőrzése
      if (!dbchk) {
        votes.push(`/k${req.body.cls}`, parseInt(votes.getData(`/k${req.body.cls}`)) + 1);
        voted.push("tokens/tokens[]", { token: req.body.id }, true);
        res.send('Rögzítve!');
      } else {
        res.send('Ezzel a tokennel már szavaztak!')
      }
    } else {
      res.send('Csak eötvösös emaillel megy a szavazás!')
    }
  } else {
    res.send('Nem tudom mit csinálsz, de nagyon úgy fest, hogy hacklesz. (JWT lejárt, integritása hibás')
  }
});




//Yandere DEV (Egyébként adatbázisok törlése)
function initialize() {
  voted.push("/tokens", [], true);
  votes.push("/k1", 0);
  votes.push("/k2", 0);
  votes.push("/k3", 0);
  votes.push("/k4", 0);
  votes.push("/k5", 0);
  votes.push("/k6", 0);
  votes.push("/k7", 0);
  votes.push("/k8", 0);
  votes.push("/k9", 0);
  votes.push("/k10", 0);
  votes.push("/k11", 0);
  votes.push("/k12", 0);
  votes.push("/k13", 0);
  votes.push("/k14", 0);
  votes.push("/k15", 0);
  votes.push("/k16", 0);
  votes.push("/k17", 0);
  votes.push("/k18", 0);
  votes.push("/k19", 0);
  votes.push("/k20", 0);
  votes.push("/k21", 0);
  votes.push("/k22", 0);
  votes.push("/k23", 0);
  votes.push("/k24", 0);
  votes.push("/k25", 0);
  votes.push("/k26", 0);
  votes.push("/k27", 0);
  votes.push("/k28", 0);
  votes.push("/k29", 0);
}



/*
Adatbázis felépítése
key osztály
k1     7.A
k2     7.B
k3     8.A
k4     8.B
k5     9.Ny
k6     9.A
k7     9.B
k8     9.C
k9     9.D
k10    9.E
k11    9.F
k12    10.A
k13    10.B
k14    10.C
k15    10.D
k16    10.E
k17    10.F
k18    11.A
k19    11.B
k20    11.C
k21    11.D
k22    11.E
k23    11.F
k24    12.A
k25    12.B
k26    12.C
k27    12.D
k28    12.E
k29    12.F
*/




server.listen(433, () => {
  initialize();
  console.log('\x1b[32m', '[EXPRESS]', "\x1b[37m", 'Alkalmazás elindítva a 433-as porton.')
});
//Készítette: Mikula Lajos
