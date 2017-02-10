/**
 * Created by liu on 17-2-10.
 */

const crypto     = require('crypto');
const fs         = require('fs');
const password   = process.argv.length > 2 && process.argv[2].length > 0 ? process.argv[2] : 'ji-life';
const inputFile  = process.argv.length > 3 && process.argv[3].length > 0 ?
    process.argv[3] : 'deployment/mysql/dump';
const outputFile = process.argv.length > 3 && process.argv[3].length > 0 ?
    process.argv[3] : 'deployment/mysql/dump.sql';
const decipher   = crypto.createDecipher('aes192', password);
const input      = fs.createReadStream(inputFile);
const output     = fs.createWriteStream(outputFile);

input.pipe(decipher).pipe(output);

