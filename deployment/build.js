'use strict';
const fs=require('fs');
const shelljs=require('shelljs');
const crypto=require('crypto');
function process_dir(dir){
	const files=fs.readdirSync(dir);
	for(let i=0;i<files.length;i++){
		let file_dir=dir+'/'+files[i];
		const stats=fs.statSync(file_dir);
		if(stats.isDirectory()){
			process_dir(file_dir);
		}
		else{
			let check_min_js=files[i].match (/\.min[\W\w]*\.js/);
			let check_js=files[i].match (/\.js$/);
			if(!check_min_js&&check_js)
			{
				let filename=file_dir.replace (/\.js$/,'');
				process_js(filename);
				break;
			}
		}
	}
}

function process_js(filename){
	const buffer=fs.readFileSync(filename+'.js');
	const hash=crypto.createHash('md5');
	hash.update(buffer);
	let signature=hash.digest('hex').substring(0,6);
	console.log(signature);
	try{
		fs.access(filename+'.min.'+signature+'.js')
	}catch(err){
		console.log('not found');
		shelljs.rm(filename + '.min.*').grep('*.js');
	}
	}
	


process_dir('../js/ji');