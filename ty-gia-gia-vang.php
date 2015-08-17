<?php
/*
Plugin Name: Ty Gia & Gia Vang
Plugin URI: http://www.tygia.com/
Description: Widget Tỷ giá & Giá Vàng by www.tygia.com
Version: 1.0
Author: Quang Nguyen
Author URI: http://www.tygia.com/
License: GPLv2 or later
*/

function register_ty_gia_gia_vang_widget() {
    register_widget( 'Ty_Gia_Gia_Vang_Widget' );
}
add_action( 'widgets_init', 'register_ty_gia_gia_vang_widget' );

class Ty_Gia_Gia_Vang_Widget extends WP_Widget {

	public $maNT = array( 'USD', 'JPY', 'EUR', 'GBP','AUD', 'CAD', 'CHF', 'DKK', 'HKD', 'INR', 'KRW', 'KWD', 'MYR', 'NOK', 'RUB', 'SAR', 'SEK', 'SGD','THB' );
    public $banks=array(
		'VIETCOM'=>'VIETCOMBANK',
		'EXIM'=>'EXIMBANK',
		'VIETIN'=>'VIETINBANK',
		'TECHCOM'=>'TECHCOMBANK',
		'AGRI'=>'AGRIBANK',
		'ACB'=>'ACB',
		'BIDV'=>'BIDV',
	   );
	   
	public function __construct() {
		parent::__construct(
			'ty_gia_gia_vang_widget', // Base ID
			'Tỷ giá & Giá Vàng', // Name
			 array( 'description' =>'Tỷ giá & Giá Vàng by www.tygia.com') // Args
		);
		
	}

	public function widget( $args, $instance ) {
		$isos='';
		foreach ($this->maNT as $value) { 
			if (1== $instance[$value] ){
				if($isos!='')
					$isos =$isos.','.$value;
				else 
					$isos=$value;
				
			}
		}
     	echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		//color
		$color=$instance['color'];
		if($color==null || (strlen($color)!=3 && strlen($color)!=6) || !preg_match('/^[a-f0-9]+$/i', $color)  ){
			$color= get_header_textcolor();
		}
		if(strlen($color)==3){
		     $color=substr($color,0,1).substr($color,0,1)
			 .substr($color,1,1).substr($color,1,1)
			 .substr($color,2,1).substr($color,2,1);
		}else if(strlen($color)!=6){
			$color='333333';
		}
		//size
		$fontsize=$instance['font_size'];
		if($fontsize==null || strlen($fontsize)<2 || strlen($fontsize)>3 || !preg_match('/^[0-9]+$/i', $fontsize)  ){
			if( strpos('Twenty',get_current_theme())==0)
				$fontsize=60;
			else
				$fontsize=80;		
		}
		echo "<iframe style='padding:0' width=".$instance['width']." height=".$instance['height']." src='http://www.tygia.com/api.php?column=1&title=0&chart=".$instance['chart']."&gold=".$instance['gold']."&rate=".$instance['rate']."&ngoaite=$isos&expand=".$instance['expand']."&color=".$color."&nganhang=".$instance['bank']."&fontsize=".$fontsize."&ngay' ></iframe>";
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['bank'] = strip_tags($new_instance['bank']);
		$instance['color'] = strip_tags($new_instance['color']);
		$instance['font_size'] = strip_tags($new_instance['font_size']);
		$instance['rate'] = !empty($new_instance['rate']) ? 1 : 0; 
		$instance['gold'] =  !empty($new_instance['gold']) ? 1 : 0; 
		$instance['chart'] =  !empty($new_instance['chart']) ? 1 : 0; 
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['expand'] =!empty($new_instance['expand']) ? 1 : 0;
		foreach ($this->maNT as $value) {
			$instance[$value] = !empty($new_instance[$value]) ? 1 : 0; 
		}

		return $instance;
	}

	public function form( $instance ) {
		
		
		
		$instance = wp_parse_args( (array) $instance, array( 'USD' => 1,'JPY'=>1,'EUR'=>1,'GBP'=>1 ,'bank'=>'','rate'=>1,'gold'=>1,'chart'=>0,'width'=>'100%','height'=>'275','expand'=>0) );
		
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Tỷ giá & Giá Vàng';
		$color = $instance['color'] ;
		$font_size = $instance['font_size'] ;
		
		foreach ($this->maNT as $value) {
			$$value = isset($instance[$value]) ? (bool) $instance[$value] :false;
		} ?>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'bank' ); ?>">Ngân hàng</label>
		<select name="<?php echo $this->get_field_name( 'bank' ); ?>" id="<?php echo $this->get_field_id( 'bank' ); ?>">
				<option  value=''>[Mới nhất]</option>"
				<?php foreach ($this->banks as $key=>$value) { 
					echo "<option ".selected( $instance['bank'], $key )." value='$key'>$value </option>";
				} ?>
		</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'color' ); ?>">Màu chữ #</label>
			<input placeholder="FFFFFF" id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" value='<?php echo esc_attr( $color ); ?>' class="checkbox" type="text">
	    	
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'font_size' ); ?>">Size chữ %</label>
			<input  placeholder="80" id="<?php echo $this->get_field_id( 'font_size' ); ?>" name="<?php echo $this->get_field_name( 'font_size' ); ?>" value='<?php echo esc_attr( $font_size); ?>' class="checkbox" type="text">
	    	
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>">width</label>
			<input style="width:70px" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value='<?php echo esc_attr( $instance['width']); ?>' class="checkbox" type="text">
	    	<label for="<?php echo $this->get_field_id( 'height' ); ?>">height</label>
			<input placeholder="270" style="width:70px" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value='<?php echo esc_attr( $instance['height']); ?>' class="checkbox" type="text">
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'expand' ); ?>" name="<?php echo $this->get_field_name( 'expand' ); ?>"<?php checked( $instance['expand'] ); ?> class="checkbox" type="checkbox">
	    	<label for="<?php echo $this->get_field_id( 'expand' ); ?>">Hiện tất cả NT</label>
		</p>	
		<p>
			<input id="<?php echo $this->get_field_id( 'chart' ); ?>" name="<?php echo $this->get_field_name( 'chart' ); ?>"<?php checked( $instance['chart'] ); ?> class="checkbox" type="checkbox">
	    	<label for="<?php echo $this->get_field_id( 'chart' ); ?>">Hiện biểu đồ</label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'gold' ); ?>" name="<?php echo $this->get_field_name( 'gold' ); ?>" <?php checked( $instance['gold'] ); ?> class="checkbox" type="checkbox">
	    	<label for="<?php echo $this->get_field_id( 'gold' ); ?>">Hiện giá vàng</label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'rate' ); ?>" name="<?php echo $this->get_field_name( 'rate' ); ?>"<?php checked( $instance['rate'] ); ?> class="checkbox" type="checkbox">
	    	<label for="<?php echo $this->get_field_id( 'rate' ); ?>">Hiện tỷ giá</label>
		</p>	
		<p style="max-height:100px;overflow-y: scroll;">
		<?php foreach ($this->maNT as $value) { ?>
			<input id="<?php echo $this->get_field_id( $value ); ?>" name="<?php echo $this->get_field_name( $value ); ?>"<?php checked( $$value ); ?> class="checkbox" type="checkbox">
			<label for="<?php echo $this->get_field_id( $value ); ?>"><?php echo $value; ?></label>
			<br>
		<?php } ?>
		</p>
		<p>
		</p>
		<?php 
	}
}
