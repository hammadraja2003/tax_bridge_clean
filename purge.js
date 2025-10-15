import { PurgeCSS } from 'purgecss'
import fs from 'fs'

const purgeCSSResult = await new PurgeCSS().purge({
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  css: [
    './public/assets/style-BVr_C8ru.css',             // your app CSS
  ],
  safelist: [
    /^fa-/,
    /^bg-/,
    /^text-/,
    /^alert-/,
    /^btn-/,
    'active',
    'show',
    'hidden',
  ],
})

fs.mkdirSync('./public/assets/cleaned', { recursive: true })
fs.writeFileSync('./public/assets/cleaned/style-BVr_C8ru.css', purgeCSSResult[1].css)

console.log('✅ Purged CSS written to public/assets/cleaned/')



// run like this 
// node purge.js
