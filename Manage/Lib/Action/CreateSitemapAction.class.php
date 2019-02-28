<?php 
    class CreateSitemapAction extends CommonAction{


        /**
         * @todo   index  生成网站地图首页视图
         */
        public function index(){
            $this->display();
        }


        /**
         * @todo   create     生成网站地图         
         */
        public function create(){
            import('Class.Sitemap','./');
            $s=new f();
            $r=$s->dir_list('./');            
            $str='<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
            $str.="<urlset>\r\n";
            foreach ($r as $k=>$v) {
                $str.="\t<url>\r\n";
                $str.="\t\t<loc>".$v[0]."</loc>\r\n";
                $str.="\t\t<lastmod>".$v[1]."</lastmod>\r\n";
                $str.="\t\t<changefreq>".$v[2]."</changefreq>\r\n";
                $str.="\t\t<priority>".$v[3]."</priority>\r\n";
                $str.="\t</url>\r\n";
            }
            $str.='</urlset>';
            $file_url=fopen('./sitemap.xml', "w");
            if($file_bytes = fwrite($file_url, $str)){
                $file_bytes = $file_bytes/1000;
                $msg = "<a href='/sitemap.xml' target='_blank'>sitemap.xml</a>已生成 文件大小：".$file_bytes."KB";
                $back_html = "<p><a href=\"javascript:history.go(-1)\">返回上一页</a>";
                $msg .= $back_html;
                echo $msg;
            }
            fclose($file_url);            
        }
    }
 ?>