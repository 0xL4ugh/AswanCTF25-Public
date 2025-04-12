const express = require('express');
const puppeteer = require('puppeteer');

const app = express();
const PORT = 3000;

app.use(express.json());

const domain = process.env.DOMAIN || '127.0.0.1';
const cookie = {
  name: 'FLAG',
  value: process.env.FLAG || 'placeholder',
  domain: domain,
  httpOnly: true
};


const sleep = (delay) => new Promise((resolve) => setTimeout(resolve, delay))

app.post('/visit', async (req, res) => {
  const { url } = req.body;
    
  if (!url || (!url.startsWith("http://") && ! url.startsWith("https://"))) {
    return res.status(400).send('Please provide a valid URL');
  }

  try {
  const browser = await puppeteer.launch({
    args: ['--no-sandbox', '--disable-setuid-sandbox'],
    timeout: 300000
  });   
 const page = await browser.newPage();
    await page.setCookie(cookie);   
    await page.goto(url,{'timeout':300000 });
    await sleep(30000);
    await browser.close();
    res.send('Page visited successfully');
  } catch (error) {
    console.error('Error visiting page:', error);
    res.status(500).send('An error occurred while visiting the page');
  }
});

app.listen(PORT, '0.0.0.0', () => {
  console.log(`Server is running on port ${PORT}`);
});

