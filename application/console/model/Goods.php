<?php
namespace app\console\model;
use think\Model;

class Goods extends Base
{
    // 指定表名,不含前缀
    protected $name = 'goods';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    //开启自动完成
    protected $auto = ['picarr']; 
    
    protected function setPicarrAttr($value)
    {
        $picarr = input('picarr/a');
        if(!empty($picarr)&&is_array($picarr))
        {
            $info = input('info/a');
            $show = input('show/a');

            $arr = array();
            foreach($picarr as $key=>$v){
                $row['img']  = $v;
                $row['info'] = $info[$key];
                $row['show'] = $show[$key];
                $arr[]=$row;
            }
            return serialize($arr); 
        }else{
            return '';
        }

    }
	
	  protected function getPicarrAttr($value)
    {
        $data = empty($value)?[]:unserialize($value);
        return $data; 
    }
	
	  protected function getAttrAttr($value)
    {
        $data = empty($value)?[]:unserialize($value);
        return $data; 
    }
	
	  protected function getGoodsAttrAttr($value)
    {
        $data = empty($value)?[]:unserialize($value);
        return json_encode($data); 
    }

    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    public static function saveVerify($data,$id = ''){
        $Model = new Goods();

        $data['goodsattr'] = '';
        $data['attr'] = '';

        if(isset($data['attrs_group'])&&isset($data['price'])){
            $list = $data['attrs_group'];
            $attr_num = count($list);
            $titles=array();
            $attrs=array();
            for($i=0;$i<$attr_num;$i++){
                if(isset($data['attrs'.$i])){
                    $title['title'] = $data['attrs_group'][$i];
                    $title['attrs'] = $data['attrs'.$i]; 

                    $titles[]=$title;
                    $attrs[]=$data['attrs'.$i];
                }
            }
            $a = self::Descartes($attrs);
            $arra=array();
            for($i=0;$i<count($a);$i++){
                  $arr=array();
                  $attrs_key=implode('_',$a[$i]);
                  $arr['price']=$data['price'][$i];
                  $arr['oldprice']=$data['oldprice'][$i];
                  $arr['homenum']=$data['homenum'][$i];
                  $arra[$attrs_key]=$arr;
            }
            $data['goodsattr'] = serialize($arra);
            $data['attr'] = serialize($titles);
      }
        
        //判断是添加还是修改
        $handle = empty($id)? false : true;

        // 调用验证器类进行数据验证
        $result = $Model->validate(true)->allowField(true)->isUpdate($handle)->save($data);
        if(false === $result){
            // 验证失败 输出错误信息
            return $Model->getError();
        }else{
            return true;
        }
    }

    public static function Descartes($t) { 
        if(count($t) == 1) //return call_user_func_array( __FUNCTION__, $t[0] ); 
         {
              $a = array_shift($t); 
              $r=array();
              foreach($a as $v){
                  $r[]=array($v);
                }
               return $r;
         }
         else{
              $a = array_shift($t); 
              if(! is_array($a)) $a = array($a);  
              $a = array_chunk($a, 1);  
              do {  
                $r = array();  
                $b = array_shift($t);  
                if(! is_array($b)) $b = array($b);  
                foreach($a as $p)  
                    foreach(array_chunk($b, 1) as $q)  
                        $r[] = array_merge($p, $q);  
                $a = $r;  
               }while($t);
               
               return $r;  
         }
   } 

}
