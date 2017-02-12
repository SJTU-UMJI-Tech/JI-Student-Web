/**
 * Created by liu on 17-2-13.
 */
"use strict";
define([
    'require', 'exports', 'module',
], function (require, exports, module) {
    
    const iconList = {
        "mp3" : "audio",
        "wma" : "audio",
        "wav" : "audio",
        "aac" : "audio",
        "mid" : "audio",
        "c"   : "code",
        "h"   : "code",
        "cpp" : "code",
        "cc"  : "code",
        "hpp" : "code",
        "py"  : "code",
        "html": "code",
        "htm" : "code",
        "js"  : "code",
        "css" : "code",
        "php" : "code",
        "jsp" : "code",
        "asp" : "code",
        "aspx": "code",
        "java": "code",
        "json": "code",
        "yaml": "code",
        "xml" : "code",
        "sql" : "code",
        "m"   : "code",
        "mm"  : "code",
        "lua" : "code",
        "rb"  : "code",
        "as"  : "code",
        "xls" : "excel",
        "xlsx": "excel",
        "csv" : "excel",
        "slk" : "excel",
        "ods" : "excel",
        "bmp" : "image",
        "png" : "image",
        "jpg" : "image",
        "jpeg": "image",
        "gif" : "image",
        "tiff": "image",
        "mp4" : "movie",
        "mpeg": "movie",
        "avi" : "movie",
        "mov" : "movie",
        "wmv" : "movie",
        "mkv" : "movie",
        "flv" : "movie",
        "rmvb": "movie",
        "3gp" : "movie",
        "pdf" : "pdf",
        "ppt" : "powerpoint",
        "pps" : "powerpoint",
        "pptx": "powerpoint",
        "ppsx": "powerpoint",
        "odp" : "powerpoint",
        "txt" : "text",
        "md"  : "text",
        "rst" : "text",
        "doc" : "word",
        "docx": "word",
        "rtf" : "word",
        "odt" : "word",
        "rar" : "zip",
        "zip" : "zip",
        "iso" : "zip",
        "7z"  : "zip",
        "tar" : "zip",
        "xz"  : "zip",
        "gz"  : "zip",
        "bz2" : "zip"
    };
    
    module.exports = {
        getIcon     : (suffix = '') => {
            if (iconList.hasOwnProperty(suffix)) {
                return `fa-file-${iconList[suffix]}-o`;
            }
            return 'fa-file-o';
        },
        processFile : (filename = '') => {
            const suffix = filename.match(/\.[\w]+$/);
            if (suffix && suffix.length > 0) {
                return module.exports.getIcon(suffix[0].replace('.', ''));
            }
            return module.exports.getIcon();
        },
        processArray: (arr) => {
            for (let i = 0; i < arr.length; i++) {
                arr[i].icon = module.exports.processFile(arr[i].name);
            }
        }
    }
    
});
