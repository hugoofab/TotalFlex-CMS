<?php

namespace TotalFlex;

class MimeType {

	protected static $fullMimetypeList = array (
		".3dm"       => array ( "x-world/x-3dmf" ) ,
		".3dmf"      => array ( "x-world/x-3dmf" ) ,
		".a"         => array ( "application/octet-stream" ) ,
		".aab"       => array ( "application/x-authorware-bin" ) ,
		".aam"       => array ( "application/x-authorware-map" ) ,
		".aas"       => array ( "application/x-authorware-seg" ) ,
		".abc"       => array ( "text/vnd.abc" ) ,
		".acgi"      => array ( "text/html" ) ,
		".afl"       => array ( "video/animaflex" ) ,
		".ai"        => array ( "application/postscript" ) ,
		".aif"       => array ( "audio/aiff" ) ,
		".aif"       => array ( "audio/x-aiff" ) ,
		".aifc"      => array ( "audio/aiff" ) ,
		".aifc"      => array ( "audio/x-aiff" ) ,
		".aiff"      => array ( "audio/aiff" ) ,
		".aiff"      => array ( "audio/x-aiff" ) ,
		".aim"       => array ( "application/x-aim" ) ,
		".aip"       => array ( "text/x-audiosoft-intra" ) ,
		".ani"       => array ( "application/x-navi-animation" ) ,
		".aos"       => array ( "application/x-nokia-9000-communicator-add-on-software" ) ,
		".aps"       => array ( "application/mime" ) ,
		".arc"       => array ( "application/octet-stream" ) ,
		".arj"       => array ( "application/arj" ) ,
		".arj"       => array ( "application/octet-stream" ) ,
		".art"       => array ( "image/x-jg" ) ,
		".asf"       => array ( "video/x-ms-asf" ) ,
		".asm"       => array ( "text/x-asm" ) ,
		".asp"       => array ( "text/asp" ) ,
		".asx"       => array ( "application/x-mplayer2" ) ,
		".asx"       => array ( "video/x-ms-asf" ) ,
		".asx"       => array ( "video/x-ms-asf-plugin" ) ,
		".au"        => array ( "audio/basic" ) ,
		".au"        => array ( "audio/x-au" ) ,
		".avi"       => array ( "application/x-troff-msvideo" ) ,
		".avi"       => array ( "video/avi" ) ,
		".avi"       => array ( "video/msvideo" ) ,
		".avi"       => array ( "video/x-msvideo" ) ,
		".avs"       => array ( "video/avs-video" ) ,
		".bcpio"     => array ( "application/x-bcpio" ) ,
		".bin"       => array ( "application/mac-binary" ) ,
		".bin"       => array ( "application/macbinary" ) ,
		".bin"       => array ( "application/octet-stream" ) ,
		".bin"       => array ( "application/x-binary" ) ,
		".bin"       => array ( "application/x-macbinary" ) ,
		".bm"        => array ( "image/bmp" ) ,
		".bmp"       => array ( "image/bmp" ) ,
		".bmp"       => array ( "image/x-windows-bmp" ) ,
		".boo"       => array ( "application/book" ) ,
		".book"      => array ( "application/book" ) ,
		".boz"       => array ( "application/x-bzip2" ) ,
		".bsh"       => array ( "application/x-bsh" ) ,
		".bz"        => array ( "application/x-bzip" ) ,
		".bz2"       => array ( "application/x-bzip2" ) ,
		".c"         => array ( "text/plain" ) ,
		".c"         => array ( "text/x-c" ) ,
		".c++"       => array ( "text/plain" ) ,
		".cat"       => array ( "application/vnd.ms-pki.seccat" ) ,
		".cc"        => array ( "text/plain" ) ,
		".cc"        => array ( "text/x-c" ) ,
		".ccad"      => array ( "application/clariscad" ) ,
		".cco"       => array ( "application/x-cocoa" ) ,
		".cdf"       => array ( "application/cdf" ) ,
		".cdf"       => array ( "application/x-cdf" ) ,
		".cdf"       => array ( "application/x-netcdf" ) ,
		".cer"       => array ( "application/pkix-cert" ) ,
		".cer"       => array ( "application/x-x509-ca-cert" ) ,
		".cha"       => array ( "application/x-chat" ) ,
		".chat"      => array ( "application/x-chat" ) ,
		".class"     => array ( "application/java" ) ,
		".class"     => array ( "application/java-byte-code" ) ,
		".class"     => array ( "application/x-java-class" ) ,
		".com"       => array ( "application/octet-stream" ) ,
		".com"       => array ( "text/plain" ) ,
		".conf"      => array ( "text/plain" ) ,
		".cpio"      => array ( "application/x-cpio" ) ,
		".cpp"       => array ( "text/x-c" ) ,
		".cpt"       => array ( "application/mac-compactpro" ) ,
		".cpt"       => array ( "application/x-compactpro" ) ,
		".cpt"       => array ( "application/x-cpt" ) ,
		".crl"       => array ( "application/pkcs-crl" ) ,
		".crl"       => array ( "application/pkix-crl" ) ,
		".crt"       => array ( "application/pkix-cert" ) ,
		".crt"       => array ( "application/x-x509-ca-cert" ) ,
		".crt"       => array ( "application/x-x509-user-cert" ) ,
		".csh"       => array ( "application/x-csh" ) ,
		".csh"       => array ( "text/x-script.csh" ) ,
		".css"       => array ( "application/x-pointplus" ) ,
		".css"       => array ( "text/css" ) ,
		".cxx"       => array ( "text/plain" ) ,
		".dcr"       => array ( "application/x-director" ) ,
		".deepv"     => array ( "application/x-deepv" ) ,
		".def"       => array ( "text/plain" ) ,
		".der"       => array ( "application/x-x509-ca-cert" ) ,
		".dif"       => array ( "video/x-dv" ) ,
		".dir"       => array ( "application/x-director" ) ,
		".dl"        => array ( "video/dl" ) ,
		".dl"        => array ( "video/x-dl" ) ,
		".doc"       => array ( "application/msword" ) ,
		".dot"       => array ( "application/msword" ) ,
		".dp"        => array ( "application/commonground" ) ,
		".drw"       => array ( "application/drafting" ) ,
		".dump"      => array ( "application/octet-stream" ) ,
		".dv"        => array ( "video/x-dv" ) ,
		".dvi"       => array ( "application/x-dvi" ) ,
		".dwf"       => array ( "drawing/x-dwf (old)" ) ,
		".dwf"       => array ( "model/vnd.dwf" ) ,
		".dwg"       => array ( "application/acad" ) ,
		".dwg"       => array ( "image/vnd.dwg" ) ,
		".dwg"       => array ( "image/x-dwg" ) ,
		".dxf"       => array ( "application/dxf" ) ,
		".dxf"       => array ( "image/vnd.dwg" ) ,
		".dxf"       => array ( "image/x-dwg" ) ,
		".dxr"       => array ( "application/x-director" ) ,
		".el"        => array ( "text/x-script.elisp" ) ,
		".elc"       => array ( "application/x-bytecode.elisp (compiled elisp)" ) ,
		".elc"       => array ( "application/x-elc" ) ,
		".env"       => array ( "application/x-envoy" ) ,
		".eps"       => array ( "application/postscript" ) ,
		".es"        => array ( "application/x-esrehber" ) ,
		".etx"       => array ( "text/x-setext" ) ,
		".evy"       => array ( "application/envoy" ) ,
		".evy"       => array ( "application/x-envoy" ) ,
		".exe"       => array ( "application/octet-stream" ) ,
		".f"         => array ( "text/plain" ) ,
		".f"         => array ( "text/x-fortran" ) ,
		".f77"       => array ( "text/x-fortran" ) ,
		".f90"       => array ( "text/plain" ) ,
		".f90"       => array ( "text/x-fortran" ) ,
		".fdf"       => array ( "application/vnd.fdf" ) ,
		".fif"       => array ( "application/fractals" ) ,
		".fif"       => array ( "image/fif" ) ,
		".fli"       => array ( "video/fli" ) ,
		".fli"       => array ( "video/x-fli" ) ,
		".flo"       => array ( "image/florian" ) ,
		".flx"       => array ( "text/vnd.fmi.flexstor" ) ,
		".fmf"       => array ( "video/x-atomic3d-feature" ) ,
		".for"       => array ( "text/plain" ) ,
		".for"       => array ( "text/x-fortran" ) ,
		".fpx"       => array ( "image/vnd.fpx" ) ,
		".fpx"       => array ( "image/vnd.net-fpx" ) ,
		".frl"       => array ( "application/freeloader" ) ,
		".funk"      => array ( "audio/make" ) ,
		".g"         => array ( "text/plain" ) ,
		".g3"        => array ( "image/g3fax" ) ,
		".gif"       => array ( "image/gif" ) ,
		".gl"        => array ( "video/gl" ) ,
		".gl"        => array ( "video/x-gl" ) ,
		".gsd"       => array ( "audio/x-gsm" ) ,
		".gsm"       => array ( "audio/x-gsm" ) ,
		".gsp"       => array ( "application/x-gsp" ) ,
		".gss"       => array ( "application/x-gss" ) ,
		".gtar"      => array ( "application/x-gtar" ) ,
		".gz"        => array ( "application/x-compressed" ) ,
		".gz"        => array ( "application/x-gzip" ) ,
		".gzip"      => array ( "application/x-gzip" ) ,
		".gzip"      => array ( "multipart/x-gzip" ) ,
		".h"         => array ( "text/plain" ) ,
		".h"         => array ( "text/x-h" ) ,
		".hdf"       => array ( "application/x-hdf" ) ,
		".help"      => array ( "application/x-helpfile" ) ,
		".hgl"       => array ( "application/vnd.hp-hpgl" ) ,
		".hh"        => array ( "text/plain" ) ,
		".hh"        => array ( "text/x-h" ) ,
		".hlb"       => array ( "text/x-script" ) ,
		".hlp"       => array ( "application/hlp" ) ,
		".hlp"       => array ( "application/x-helpfile" ) ,
		".hlp"       => array ( "application/x-winhelp" ) ,
		".hpg"       => array ( "application/vnd.hp-hpgl" ) ,
		".hpgl"      => array ( "application/vnd.hp-hpgl" ) ,
		".hqx"       => array ( "application/binhex" ) ,
		".hqx"       => array ( "application/binhex4" ) ,
		".hqx"       => array ( "application/mac-binhex" ) ,
		".hqx"       => array ( "application/mac-binhex40" ) ,
		".hqx"       => array ( "application/x-binhex40" ) ,
		".hqx"       => array ( "application/x-mac-binhex40" ) ,
		".hta"       => array ( "application/hta" ) ,
		".htc"       => array ( "text/x-component" ) ,
		".htm"       => array ( "text/html" ) ,
		".html"      => array ( "text/html" ) ,
		".htmls"     => array ( "text/html" ) ,
		".htt"       => array ( "text/webviewhtml" ) ,
		".htx"       => array ( "text/html" ) ,
		".ice"       => array ( "x-conference/x-cooltalk" ) ,
		".ico"       => array ( "image/x-icon" ) ,
		".idc"       => array ( "text/plain" ) ,
		".ief"       => array ( "image/ief" ) ,
		".iefs"      => array ( "image/ief" ) ,
		".iges"      => array ( "application/iges" ) ,
		".iges"      => array ( "model/iges" ) ,
		".igs"       => array ( "application/iges" ) ,
		".igs"       => array ( "model/iges" ) ,
		".ima"       => array ( "application/x-ima" ) ,
		".imap"      => array ( "application/x-httpd-imap" ) ,
		".inf"       => array ( "application/inf" ) ,
		".ins"       => array ( "application/x-internett-signup" ) ,
		".ip"        => array ( "application/x-ip2" ) ,
		".isu"       => array ( "video/x-isvideo" ) ,
		".it"        => array ( "audio/it" ) ,
		".iv"        => array ( "application/x-inventor" ) ,
		".ivr"       => array ( "i-world/i-vrml" ) ,
		".ivy"       => array ( "application/x-livescreen" ) ,
		".jam"       => array ( "audio/x-jam" ) ,
		".jav"       => array ( "text/plain" ) ,
		".jav"       => array ( "text/x-java-source" ) ,
		".java"      => array ( "text/plain" ) ,
		".java"      => array ( "text/x-java-source" ) ,
		".jcm"       => array ( "application/x-java-commerce" ) ,
		".jfif"      => array ( "image/jpeg" ) ,
		".jfif"      => array ( "image/pjpeg" ) ,
		".jfif-tbnl" => array ( "image/jpeg" ) ,
		".jpe"       => array ( "image/jpeg" ) ,
		".jpe"       => array ( "image/pjpeg" ) ,
		".jpeg"      => array ( "image/jpeg" ) ,
		".jpeg"      => array ( "image/pjpeg" ) ,
		".jpg"       => array ( "image/jpeg" , "image/pjpeg" ) ,
		".jps"       => array ( "image/x-jps" ) ,
		".js"        => array ( "application/x-javascript" , "application/javascript" , "application/ecmascript" , "text/javascript" , "text/ecmascript" ) ,
		".jut"       => array ( "image/jutvision" ) ,
		".kar"       => array ( "audio/midi" ) ,
		".kar"       => array ( "music/x-karaoke" ) ,
		".ksh"       => array ( "application/x-ksh" ) ,
		".ksh"       => array ( "text/x-script.ksh" ) ,
		".la"        => array ( "audio/nspaudio" ) ,
		".la"        => array ( "audio/x-nspaudio" ) ,
		".lam"       => array ( "audio/x-liveaudio" ) ,
		".latex"     => array ( "application/x-latex" ) ,
		".lha"       => array ( "application/lha" ) ,
		".lha"       => array ( "application/octet-stream" ) ,
		".lha"       => array ( "application/x-lha" ) ,
		".lhx"       => array ( "application/octet-stream" ) ,
		".list"      => array ( "text/plain" ) ,
		".lma"       => array ( "audio/nspaudio" ) ,
		".lma"       => array ( "audio/x-nspaudio" ) ,
		".log"       => array ( "text/plain" ) ,
		".lsp"       => array ( "application/x-lisp" ) ,
		".lsp"       => array ( "text/x-script.lisp" ) ,
		".lst"       => array ( "text/plain" ) ,
		".lsx"       => array ( "text/x-la-asf" ) ,
		".ltx"       => array ( "application/x-latex" ) ,
		".lzh"       => array ( "application/octet-stream" ) ,
		".lzh"       => array ( "application/x-lzh" ) ,
		".lzx"       => array ( "application/lzx" ) ,
		".lzx"       => array ( "application/octet-stream" ) ,
		".lzx"       => array ( "application/x-lzx" ) ,
		".m"         => array ( "text/plain" ) ,
		".m"         => array ( "text/x-m" ) ,
		".m1v"       => array ( "video/mpeg" ) ,
		".m2a"       => array ( "audio/mpeg" ) ,
		".m2v"       => array ( "video/mpeg" ) ,
		".m3u"       => array ( "audio/x-mpequrl" ) ,
		".man"       => array ( "application/x-troff-man" ) ,
		".map"       => array ( "application/x-navimap" ) ,
		".mar"       => array ( "text/plain" ) ,
		".mbd"       => array ( "application/mbedlet" ) ,
		".mc$"       => array ( "application/x-magic-cap-package-1.0" ) ,
		".mcd"       => array ( "application/mcad" ) ,
		".mcd"       => array ( "application/x-mathcad" ) ,
		".mcf"       => array ( "image/vasa" ) ,
		".mcf"       => array ( "text/mcf" ) ,
		".mcp"       => array ( "application/netmc" ) ,
		".me"        => array ( "application/x-troff-me" ) ,
		".mht"       => array ( "message/rfc822" ) ,
		".mhtml"     => array ( "message/rfc822" ) ,
		".mid"       => array ( "application/x-midi" ) ,
		".mid"       => array ( "audio/midi" ) ,
		".mid"       => array ( "audio/x-mid" ) ,
		".mid"       => array ( "audio/x-midi" ) ,
		".mid"       => array ( "music/crescendo" ) ,
		".mid"       => array ( "x-music/x-midi" ) ,
		".midi"      => array ( "application/x-midi" ) ,
		".midi"      => array ( "audio/midi" ) ,
		".midi"      => array ( "audio/x-mid" ) ,
		".midi"      => array ( "audio/x-midi" ) ,
		".midi"      => array ( "music/crescendo" ) ,
		".midi"      => array ( "x-music/x-midi" ) ,
		".mif"       => array ( "application/x-frame" ) ,
		".mif"       => array ( "application/x-mif" ) ,
		".mime"      => array ( "message/rfc822" ) ,
		".mime"      => array ( "www/mime" ) ,
		".mjf"       => array ( "audio/x-vnd.audioexplosion.mjuicemediafile" ) ,
		".mjpg"      => array ( "video/x-motion-jpeg" ) ,
		".mm"        => array ( "application/base64" ) ,
		".mm"        => array ( "application/x-meme" ) ,
		".mme"       => array ( "application/base64" ) ,
		".mod"       => array ( "audio/mod" ) ,
		".mod"       => array ( "audio/x-mod" ) ,
		".moov"      => array ( "video/quicktime" ) ,
		".mov"       => array ( "video/quicktime" ) ,
		".movie"     => array ( "video/x-sgi-movie" ) ,
		".mp2"       => array ( "audio/mpeg" ) ,
		".mp2"       => array ( "audio/x-mpeg" ) ,
		".mp2"       => array ( "video/mpeg" ) ,
		".mp2"       => array ( "video/x-mpeg" ) ,
		".mp2"       => array ( "video/x-mpeq2a" ) ,
		".mp3"       => array ( "audio/mpeg3" ) ,
		".mp3"       => array ( "audio/x-mpeg-3" ) ,
		".mp3"       => array ( "video/mpeg" ) ,
		".mp3"       => array ( "video/x-mpeg" ) ,
		".mpa"       => array ( "audio/mpeg" ) ,
		".mpa"       => array ( "video/mpeg" ) ,
		".mpc"       => array ( "application/x-project" ) ,
		".mpe"       => array ( "video/mpeg" ) ,
		".mpeg"      => array ( "video/mpeg" ) ,
		".mpg"       => array ( "audio/mpeg" ) ,
		".mpg"       => array ( "video/mpeg" ) ,
		".mpga"      => array ( "audio/mpeg" ) ,
		".mpp"       => array ( "application/vnd.ms-project" ) ,
		".mpt"       => array ( "application/x-project" ) ,
		".mpv"       => array ( "application/x-project" ) ,
		".mpx"       => array ( "application/x-project" ) ,
		".mrc"       => array ( "application/marc" ) ,
		".ms"        => array ( "application/x-troff-ms" ) ,
		".mv"        => array ( "video/x-sgi-movie" ) ,
		".my"        => array ( "audio/make" ) ,
		".mzz"       => array ( "application/x-vnd.audioexplosion.mzz" ) ,
		".nap"       => array ( "image/naplps" ) ,
		".naplps"    => array ( "image/naplps" ) ,
		".nc"        => array ( "application/x-netcdf" ) ,
		".ncm"       => array ( "application/vnd.nokia.configuration-message" ) ,
		".nif"       => array ( "image/x-niff" ) ,
		".niff"      => array ( "image/x-niff" ) ,
		".nix"       => array ( "application/x-mix-transfer" ) ,
		".nsc"       => array ( "application/x-conference" ) ,
		".nvd"       => array ( "application/x-navidoc" ) ,
		".o"         => array ( "application/octet-stream" ) ,
		".oda"       => array ( "application/oda" ) ,
		".omc"       => array ( "application/x-omc" ) ,
		".omcd"      => array ( "application/x-omcdatamaker" ) ,
		".omcr"      => array ( "application/x-omcregerator" ) ,
		".p"         => array ( "text/x-pascal" ) ,
		".p10"       => array ( "application/pkcs10" ) ,
		".p10"       => array ( "application/x-pkcs10" ) ,
		".p12"       => array ( "application/pkcs-12" ) ,
		".p12"       => array ( "application/x-pkcs12" ) ,
		".p7a"       => array ( "application/x-pkcs7-signature" ) ,
		".p7c"       => array ( "application/pkcs7-mime" ) ,
		".p7c"       => array ( "application/x-pkcs7-mime" ) ,
		".p7m"       => array ( "application/pkcs7-mime" ) ,
		".p7m"       => array ( "application/x-pkcs7-mime" ) ,
		".p7r"       => array ( "application/x-pkcs7-certreqresp" ) ,
		".p7s"       => array ( "application/pkcs7-signature" ) ,
		".part"      => array ( "application/pro_eng" ) ,
		".pas"       => array ( "text/pascal" ) ,
		".pbm"       => array ( "image/x-portable-bitmap" ) ,
		".pcl"       => array ( "application/vnd.hp-pcl" ) ,
		".pcl"       => array ( "application/x-pcl" ) ,
		".pct"       => array ( "image/x-pict" ) ,
		".pcx"       => array ( "image/x-pcx" ) ,
		".pdb"       => array ( "chemical/x-pdb" ) ,
		".pdf"       => array ( "application/pdf" ) ,
		".pfunk"     => array ( "audio/make" ) ,
		".pfunk"     => array ( "audio/make.my.funk" ) ,
		".pgm"       => array ( "image/x-portable-graymap" ) ,
		".pgm"       => array ( "image/x-portable-greymap" ) ,
		".pic"       => array ( "image/pict" ) ,
		".pict"      => array ( "image/pict" ) ,
		".pkg"       => array ( "application/x-newton-compatible-pkg" ) ,
		".pko"       => array ( "application/vnd.ms-pki.pko" ) ,
		".pl"        => array ( "text/plain" ) ,
		".pl"        => array ( "text/x-script.perl" ) ,
		".plx"       => array ( "application/x-pixclscript" ) ,
		".pm"        => array ( "image/x-xpixmap" ) ,
		".pm"        => array ( "text/x-script.perl-module" ) ,
		".pm4"       => array ( "application/x-pagemaker" ) ,
		".pm5"       => array ( "application/x-pagemaker" ) ,
		".png"       => array ( "image/png" ) ,
		".pnm"       => array ( "application/x-portable-anymap" ) ,
		".pnm"       => array ( "image/x-portable-anymap" ) ,
		".pot"       => array ( "application/mspowerpoint" ) ,
		".pot"       => array ( "application/vnd.ms-powerpoint" ) ,
		".pov"       => array ( "model/x-pov" ) ,
		".ppa"       => array ( "application/vnd.ms-powerpoint" ) ,
		".ppm"       => array ( "image/x-portable-pixmap" ) ,
		".pps"       => array ( "application/mspowerpoint" ) ,
		".pps"       => array ( "application/vnd.ms-powerpoint" ) ,
		".ppt"       => array ( "application/mspowerpoint" ) ,
		".ppt"       => array ( "application/powerpoint" ) ,
		".ppt"       => array ( "application/vnd.ms-powerpoint" ) ,
		".ppt"       => array ( "application/x-mspowerpoint" ) ,
		".ppz"       => array ( "application/mspowerpoint" ) ,
		".pre"       => array ( "application/x-freelance" ) ,
		".prt"       => array ( "application/pro_eng" ) ,
		".ps"        => array ( "application/postscript" ) ,
		".psd"       => array ( "application/octet-stream" ) ,
		".pvu"       => array ( "paleovu/x-pv" ) ,
		".pwz"       => array ( "application/vnd.ms-powerpoint" ) ,
		".py"        => array ( "text/x-script.phyton" ) ,
		".pyc"       => array ( "application/x-bytecode.python" ) ,
		".qcp"       => array ( "audio/vnd.qcelp" ) ,
		".qd3"       => array ( "x-world/x-3dmf" ) ,
		".qd3d"      => array ( "x-world/x-3dmf" ) ,
		".qif"       => array ( "image/x-quicktime" ) ,
		".qt"        => array ( "video/quicktime" ) ,
		".qtc"       => array ( "video/x-qtc" ) ,
		".qti"       => array ( "image/x-quicktime" ) ,
		".qtif"      => array ( "image/x-quicktime" ) ,
		".ra"        => array ( "audio/x-pn-realaudio" ) ,
		".ra"        => array ( "audio/x-pn-realaudio-plugin" ) ,
		".ra"        => array ( "audio/x-realaudio" ) ,
		".ram"       => array ( "audio/x-pn-realaudio" ) ,
		".ras"       => array ( "application/x-cmu-raster" ) ,
		".ras"       => array ( "image/cmu-raster" ) ,
		".ras"       => array ( "image/x-cmu-raster" ) ,
		".rast"      => array ( "image/cmu-raster" ) ,
		".rexx"      => array ( "text/x-script.rexx" ) ,
		".rf"        => array ( "image/vnd.rn-realflash" ) ,
		".rgb"       => array ( "image/x-rgb" ) ,
		".rm"        => array ( "application/vnd.rn-realmedia" ) ,
		".rm"        => array ( "audio/x-pn-realaudio" ) ,
		".rmi"       => array ( "audio/mid" ) ,
		".rmm"       => array ( "audio/x-pn-realaudio" ) ,
		".rmp"       => array ( "audio/x-pn-realaudio" ) ,
		".rmp"       => array ( "audio/x-pn-realaudio-plugin" ) ,
		".rng"       => array ( "application/ringing-tones" ) ,
		".rng"       => array ( "application/vnd.nokia.ringing-tone" ) ,
		".rnx"       => array ( "application/vnd.rn-realplayer" ) ,
		".roff"      => array ( "application/x-troff" ) ,
		".rp"        => array ( "image/vnd.rn-realpix" ) ,
		".rpm"       => array ( "audio/x-pn-realaudio-plugin" ) ,
		".rt"        => array ( "text/richtext" ) ,
		".rt"        => array ( "text/vnd.rn-realtext" ) ,
		".rtf"       => array ( "application/rtf" ) ,
		".rtf"       => array ( "application/x-rtf" ) ,
		".rtf"       => array ( "text/richtext" ) ,
		".rtx"       => array ( "application/rtf" ) ,
		".rtx"       => array ( "text/richtext" ) ,
		".rv"        => array ( "video/vnd.rn-realvideo" ) ,
		".s"         => array ( "text/x-asm" ) ,
		".s3m"       => array ( "audio/s3m" ) ,
		".saveme"    => array ( "application/octet-stream" ) ,
		".sbk"       => array ( "application/x-tbook" ) ,
		".scm"       => array ( "application/x-lotusscreencam" ) ,
		".scm"       => array ( "text/x-script.guile" ) ,
		".scm"       => array ( "text/x-script.scheme" ) ,
		".scm"       => array ( "video/x-scm" ) ,
		".sdml"      => array ( "text/plain" ) ,
		".sdp"       => array ( "application/sdp" ) ,
		".sdp"       => array ( "application/x-sdp" ) ,
		".sdr"       => array ( "application/sounder" ) ,
		".sea"       => array ( "application/sea" ) ,
		".sea"       => array ( "application/x-sea" ) ,
		".set"       => array ( "application/set" ) ,
		".sgm"       => array ( "text/sgml" ) ,
		".sgm"       => array ( "text/x-sgml" ) ,
		".sgml"      => array ( "text/sgml" ) ,
		".sgml"      => array ( "text/x-sgml" ) ,
		".sh"        => array ( "application/x-bsh" ) ,
		".sh"        => array ( "application/x-sh" ) ,
		".sh"        => array ( "application/x-shar" ) ,
		".sh"        => array ( "text/x-script.sh" ) ,
		".shar"      => array ( "application/x-bsh" ) ,
		".shar"      => array ( "application/x-shar" ) ,
		".shtml"     => array ( "text/html" ) ,
		".shtml"     => array ( "text/x-server-parsed-html" ) ,
		".sid"       => array ( "audio/x-psid" ) ,
		".sit"       => array ( "application/x-sit" ) ,
		".sit"       => array ( "application/x-stuffit" ) ,
		".skd"       => array ( "application/x-koan" ) ,
		".skm"       => array ( "application/x-koan" ) ,
		".skp"       => array ( "application/x-koan" ) ,
		".skt"       => array ( "application/x-koan" ) ,
		".sl"        => array ( "application/x-seelogo" ) ,
		".smi"       => array ( "application/smil" ) ,
		".smil"      => array ( "application/smil" ) ,
		".snd"       => array ( "audio/basic" ) ,
		".snd"       => array ( "audio/x-adpcm" ) ,
		".sol"       => array ( "application/solids" ) ,
		".spc"       => array ( "application/x-pkcs7-certificates" ) ,
		".spc"       => array ( "text/x-speech" ) ,
		".spl"       => array ( "application/futuresplash" ) ,
		".spr"       => array ( "application/x-sprite" ) ,
		".sprite"    => array ( "application/x-sprite" ) ,
		".src"       => array ( "application/x-wais-source" ) ,
		".ssi"       => array ( "text/x-server-parsed-html" ) ,
		".ssm"       => array ( "application/streamingmedia" ) ,
		".sst"       => array ( "application/vnd.ms-pki.certstore" ) ,
		".step"      => array ( "application/step" ) ,
		".stl"       => array ( "application/sla" ) ,
		".stl"       => array ( "application/vnd.ms-pki.stl" ) ,
		".stl"       => array ( "application/x-navistyle" ) ,
		".stp"       => array ( "application/step" ) ,
		".sv4cpio"   => array ( "application/x-sv4cpio" ) ,
		".sv4crc"    => array ( "application/x-sv4crc" ) ,
		".svf"       => array ( "image/vnd.dwg" ) ,
		".svf"       => array ( "image/x-dwg" ) ,
		".svr"       => array ( "application/x-world" ) ,
		".svr"       => array ( "x-world/x-svr" ) ,
		".swf"       => array ( "application/x-shockwave-flash" ) ,
		".t"         => array ( "application/x-troff" ) ,
		".talk"      => array ( "text/x-speech" ) ,
		".tar"       => array ( "application/x-tar" ) ,
		".tbk"       => array ( "application/toolbook" ) ,
		".tbk"       => array ( "application/x-tbook" ) ,
		".tcl"       => array ( "application/x-tcl" ) ,
		".tcl"       => array ( "text/x-script.tcl" ) ,
		".tcsh"      => array ( "text/x-script.tcsh" ) ,
		".tex"       => array ( "application/x-tex" ) ,
		".texi"      => array ( "application/x-texinfo" ) ,
		".texinfo"   => array ( "application/x-texinfo" ) ,
		".text"      => array ( "application/plain" ) ,
		".text"      => array ( "text/plain" ) ,
		".tgz"       => array ( "application/gnutar" ) ,
		".tgz"       => array ( "application/x-compressed" ) ,
		".tif"       => array ( "image/tiff" ) ,
		".tif"       => array ( "image/x-tiff" ) ,
		".tiff"      => array ( "image/tiff" ) ,
		".tiff"      => array ( "image/x-tiff" ) ,
		".tr"        => array ( "application/x-troff" ) ,
		".tsi"       => array ( "audio/tsp-audio" ) ,
		".tsp"       => array ( "application/dsptype" ) ,
		".tsp"       => array ( "audio/tsplayer" ) ,
		".tsv"       => array ( "text/tab-separated-values" ) ,
		".turbot"    => array ( "image/florian" ) ,
		".txt"       => array ( "text/plain" ) ,
		".uil"       => array ( "text/x-uil" ) ,
		".uni"       => array ( "text/uri-list" ) ,
		".unis"      => array ( "text/uri-list" ) ,
		".unv"       => array ( "application/i-deas" ) ,
		".uri"       => array ( "text/uri-list" ) ,
		".uris"      => array ( "text/uri-list" ) ,
		".ustar"     => array ( "application/x-ustar" ) ,
		".ustar"     => array ( "multipart/x-ustar" ) ,
		".uu"        => array ( "application/octet-stream" ) ,
		".uu"        => array ( "text/x-uuencode" ) ,
		".uue"       => array ( "text/x-uuencode" ) ,
		".vcd"       => array ( "application/x-cdlink" ) ,
		".vcs"       => array ( "text/x-vcalendar" ) ,
		".vda"       => array ( "application/vda" ) ,
		".vdo"       => array ( "video/vdo" ) ,
		".vew"       => array ( "application/groupwise" ) ,
		".viv"       => array ( "video/vivo" ) ,
		".viv"       => array ( "video/vnd.vivo" ) ,
		".vivo"      => array ( "video/vivo" ) ,
		".vivo"      => array ( "video/vnd.vivo" ) ,
		".vmd"       => array ( "application/vocaltec-media-desc" ) ,
		".vmf"       => array ( "application/vocaltec-media-file" ) ,
		".voc"       => array ( "audio/voc" ) ,
		".voc"       => array ( "audio/x-voc" ) ,
		".vos"       => array ( "video/vosaic" ) ,
		".vox"       => array ( "audio/voxware" ) ,
		".vqe"       => array ( "audio/x-twinvq-plugin" ) ,
		".vqf"       => array ( "audio/x-twinvq" ) ,
		".vql"       => array ( "audio/x-twinvq-plugin" ) ,
		".vrml"      => array ( "application/x-vrml" ) ,
		".vrml"      => array ( "model/vrml" ) ,
		".vrml"      => array ( "x-world/x-vrml" ) ,
		".vrt"       => array ( "x-world/x-vrt" ) ,
		".vsd"       => array ( "application/x-visio" ) ,
		".vst"       => array ( "application/x-visio" ) ,
		".vsw"       => array ( "application/x-visio" ) ,
		".w60"       => array ( "application/wordperfect6.0" ) ,
		".w61"       => array ( "application/wordperfect6.1" ) ,
		".w6w"       => array ( "application/msword" ) ,
		".wav"       => array ( "audio/wav" ) ,
		".wav"       => array ( "audio/x-wav" ) ,
		".wb1"       => array ( "application/x-qpro" ) ,
		".wbmp"      => array ( "image/vnd.wap.wbmp" ) ,
		".web"       => array ( "application/vnd.xara" ) ,
		".wiz"       => array ( "application/msword" ) ,
		".wk1"       => array ( "application/x-123" ) ,
		".wmf"       => array ( "windows/metafile" ) ,
		".wml"       => array ( "text/vnd.wap.wml" ) ,
		".wmlc"      => array ( "application/vnd.wap.wmlc" ) ,
		".wmls"      => array ( "text/vnd.wap.wmlscript" ) ,
		".wmlsc"     => array ( "application/vnd.wap.wmlscriptc" ) ,
		".word"      => array ( "application/msword" ) ,
		".wp"        => array ( "application/wordperfect" ) ,
		".wp5"       => array ( "application/wordperfect" ) ,
		".wp5"       => array ( "application/wordperfect6.0" ) ,
		".wp6"       => array ( "application/wordperfect" ) ,
		".wpd"       => array ( "application/wordperfect" ) ,
		".wpd"       => array ( "application/x-wpwin" ) ,
		".wq1"       => array ( "application/x-lotus" ) ,
		".wri"       => array ( "application/mswrite" ) ,
		".wri"       => array ( "application/x-wri" ) ,
		".wrl"       => array ( "application/x-world" ) ,
		".wrl"       => array ( "model/vrml" ) ,
		".wrl"       => array ( "x-world/x-vrml" ) ,
		".wrz"       => array ( "model/vrml" ) ,
		".wrz"       => array ( "x-world/x-vrml" ) ,
		".wsc"       => array ( "text/scriplet" ) ,
		".wsrc"      => array ( "application/x-wais-source" ) ,
		".wtk"       => array ( "application/x-wintalk" ) ,
		".xbm"       => array ( "image/x-xbitmap" ) ,
		".xbm"       => array ( "image/x-xbm" ) ,
		".xbm"       => array ( "image/xbm" ) ,
		".xdr"       => array ( "video/x-amt-demorun" ) ,
		".xgz"       => array ( "xgl/drawing" ) ,
		".xif"       => array ( "image/vnd.xiff" ) ,
		".xl"        => array ( "application/excel" ) ,
		".xla"       => array ( "application/excel" ) ,
		".xla"       => array ( "application/x-excel" ) ,
		".xla"       => array ( "application/x-msexcel" ) ,
		".xlb"       => array ( "application/excel" ) ,
		".xlb"       => array ( "application/vnd.ms-excel" ) ,
		".xlb"       => array ( "application/x-excel" ) ,
		".xlc"       => array ( "application/excel" ) ,
		".xlc"       => array ( "application/vnd.ms-excel" ) ,
		".xlc"       => array ( "application/x-excel" ) ,
		".xld"       => array ( "application/excel" ) ,
		".xld"       => array ( "application/x-excel" ) ,
		".xlk"       => array ( "application/excel" ) ,
		".xlk"       => array ( "application/x-excel" ) ,
		".xll"       => array ( "application/excel" ) ,
		".xll"       => array ( "application/vnd.ms-excel" ) ,
		".xll"       => array ( "application/x-excel" ) ,
		".xlm"       => array ( "application/excel" ) ,
		".xlm"       => array ( "application/vnd.ms-excel" ) ,
		".xlm"       => array ( "application/x-excel" ) ,
		".xls"       => array ( "application/excel" , "application/vnd.ms-excel" , "application/x-excel", "application/x-msexcel" ) ,
		".xlt"       => array ( "application/excel" ) ,
		".xlt"       => array ( "application/x-excel" ) ,
		".xlv"       => array ( "application/excel" , "application/x-excel" ) ,
		".xlw"       => array ( "application/excel" , "application/vnd.ms-excel" , "application/x-excel" , "application/x-msexcel" ) , 
		".xm"        => array ( "audio/xm" ) ,
		".xml"       => array ( "application/xml" , "text/xml" ) ,
		".xmz"       => array ( "xgl/movie" ) ,
		".xpix"      => array ( "application/x-vnd.ls-xpix" ) ,
		".xpm"       => array ( "image/x-xpixmap" , "image/xpm" ) ,
		".x-png"     => array ( "image/png" ) ,
		".xsr"       => array ( "video/x-amt-showrun" ) ,
		".xwd"       => array ( "image/x-xwd" , "image/x-xwindowdump" ) ,
		".xyz"       => array ( "chemical/x-pdb" ) ,
		".z"         => array ( "application/x-compress" , "application/x-compressed") ,
		".zip"       => array ( 
			"application/x-compressed" ,
			"application/x-zip-compressed" ,
			"application/zip" ,
			"multipart/x-zip" 
		) ,
		".zoo"       => array ( "application/octet-stream" ) ,
		".zsh"       => array ( "text/x-script.zsh" )
	);

	public static function extensionMatchMimeType ( $extension , $mimeType ) {

		if ( !isset ( self::$fullMimetypeList[$extension] ) ) throw new \Exception ( "Unknown extension" );

		foreach ( self::$fullMimetypeList[$extension] as $mime ) {
			if ( $mime === $mimeType ) return true ;
		}

		return false ;

	}

	public static function extractExt ( $filename ) {
		return preg_replace ( '/^(.*)(\.\w+)$/' , "$2" , $filename );
	}

}