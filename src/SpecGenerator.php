<?php

namespace Printerous\SpecGenerator;

class SpecGenerator
{
    function generate($type, $orderDetail, $sku = null){
        $spec = array();

        //validate
        if(isset($orderDetail) && !is_object($orderDetail)){
            throw new \Exception("Order detail must be an object");
        }
        if(isset($sku) && !is_object($sku)){
            throw new \Exception("Sku must be an object");
        }
        if(!is_object($orderDetail->project_data)){
            $orderDetail->project_data = json_decode($orderDetail->project_data);
            if(is_null($orderDetail->project_data)){
                throw new \Exception("Invalid project data");
            }
        }

        switch($type){
            case "v3":
                $spec = $this->generateV3($orderDetail);
                break;
            case "arterous":
                $spec = $this->generateShop($orderDetail, $sku);
                break;
            case "shop":
                $spec = $this->generateShop($orderDetail, $sku);
                break;
            case "moments":
                $spec = $this->generateMoments($orderDetail);
                break;
            case "panorama":
                $spec = $this->generatePanorama($orderDetail);
                break;
            case "custom_orders":
                $spec = $this->generateV3($orderDetail);
                break;
        }

        foreach($spec as $key=>$val){
            $spec[$key] = ucwords($val);
        }

        return $spec;
    }

    private function generatePanorama($orderDetail){
        $product = $orderDetail->project_data->type;
        $project_data = $orderDetail->project_data;
        $spec = array();
        switch($product){
            case "canvas":
                $spec['title'] = "Canvas";
                if(@$project_data->size) $spec['size'] = "Size: ".ucwords($project_data->size);
                if(@$project_data->layout) $spec['layout'] = "Layout: ".$this->layoutImagePanorama($project_data->layout);
                break;
        }
        return $spec;
    }

    private function layoutImagePanorama($layout){
        $desc = "";
        switch($layout){
            case "layout1": $desc = "1x1 Image"; break;
            case "layout2": $desc = "2x2 Image"; break;
            case "layout3": $desc = "3x3 Image"; break;
        }
        return $desc;
    }

    private function generateShop($orderDetail,$sku){
        $product = $this->getSkuType($orderDetail->sku_id);
        $project_data = $orderDetail->project_data;
        $spec = array();
        if(@$project_data->material) $spec['type'] = "Material: ".$project_data->material;
        if(@$project_data->size) $spec['size'] = "Size: ".$project_data->size;
        if(@$project_data->sides) $spec['sides'] = "Sides: ".$project_data->sides;
        switch ($product) {
            case 'canvas_art':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'poster':
                if(@$sku->title) $spec['title'] = "Poster ".$sku->title;
                break;
            case 'tshirt':
                if(@$sku->title) $spec['title'] = $sku->title;
                if(@$project_data->colorName) $spec['color'] = "Color: ".ucwords($project_data->colorName);
                if(@$project_data->size) $spec['size'] = "Size: ".ucwords($project_data->size);
                break;
            case 'notebook_a5':
                if(@$sku->title) $spec['title'] = $sku->title;
                if(@$project_data->paper) $spec['paper'] = "Paper: ".ucwords($project_data->paper);
                break;
            case 'notebook_pocket':
                if(@$sku->title) $spec['title'] = $sku->title;
                if(@$project_data->paper) $spec['paper'] = "Paper: ".ucwords($project_data->paper);
                break;
            case 'frame_art':
                if(@$sku->title) $spec['title'] = $sku->title;
                $frametypes =  array(
                    'black' => 'Solid Black',
                    'white' => 'Solid White',
                    'darkwood' => 'Dark Wood',
                    'lightwood' => 'Light Wood',
                    'classicblack' => 'Classic Black',
                    'classicwhite' => 'Classic White'
                );
                if(@$project_data->type) $spec['frame'] = "Frame: ".@$frametypes[$project_data->type]?:"Unknown";
                if(@$project_data->layout) $spec['layout'] = "Layout: ".$project_data->layout."%";
                break;
            case 'gadget_case':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'greetingcards':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'postcards':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'magnets':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'pillow':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'totebag':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'laptop_skins':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'mug':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'tumblr':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
            case 'scarf':
                if(@$sku->title) $spec['title'] = $sku->title;
                break;
        }
        return $spec;
    }

    private function getSkuType($skuId){
        $canvas_art_type = array(1,2,3,55,56,57,4,5,6,7,8,9,10,11,12,13); // 16 skus
        $pillow_type = array(14,15); // 2 skus
        $notebook_a5 = array(16);
        $notebook_pocket = array(17);
        $tshirt = array(26,67);
        $postcards = array(27,28,29); // 3 skus
        $greetingcards = array(30,31,32); // 3 skus
        $laptop_skins = array(48,49,50,51,58,59,60,61); // 8 skus
        $totebag = array(53);
        $magnets = array(54);
        $iphone4 = array(18);
        $iphone5 = array(19);
        $iphone6 = array(20);
        $iphone6plus = array(21);
        $ipad234 = array(22);
        $ipadmini = array(23);
        $galaxys4 = array(24);
        $galaxys5 = array(25);
        $galaxys7 = array(66, 72);
        $gadgets = array(18,19,20,21,22,23,24,25,66,68,71);
        $frame_art = array(33,34,35,36,37,41,42,38,39,40,46,47,43,44,45);
        $poster = array(52,62,63);
        $mug = array(64);
        $tumblr = array(65, 69);
        $scarf = array(70);

        if(in_array($skuId, $canvas_art_type)) return 'canvas_art';
        if(in_array($skuId, $pillow_type)) return 'pillow';
        if(in_array($skuId, $notebook_a5)) return 'notebook_a5';
        if(in_array($skuId, $notebook_pocket)) return 'notebook_pocket';
        if(in_array($skuId, $tshirt)) return 'tshirt';
        if(in_array($skuId, $postcards)) return 'postcards';
        if(in_array($skuId, $greetingcards)) return 'greetingcards';
        if(in_array($skuId, $laptop_skins)) return 'laptop_skins';
        if(in_array($skuId, $totebag)) return 'totebag';
        if(in_array($skuId, $magnets)) return 'magnets';
        if(in_array($skuId, $gadgets)) return 'gadget_case';
        if(in_array($skuId, $iphone4)) return 'iphone4';
        if(in_array($skuId, $iphone5)) return 'iphone5';
        if(in_array($skuId, $iphone6)) return 'iphone6';
        if(in_array($skuId, $iphone6plus)) return 'iphone6plus';
        if(in_array($skuId, $ipad234)) return 'ipad234';
        if(in_array($skuId, $ipadmini)) return 'ipadmini';
        if(in_array($skuId, $galaxys4)) return 'galaxys4';
        if(in_array($skuId, $galaxys5)) return 'galaxys5';
        if(in_array($skuId, $galaxys7)) return 'galaxys7';
        if(in_array($skuId, $frame_art)) return 'frame_art';
        if(in_array($skuId, $poster)) return 'poster';
        if(in_array($skuId, $mug)) return 'mug';
        if(in_array($skuId, $tumblr)) return 'tumblr';
        if(in_array($skuId, $scarf)) return 'scarf';
    }

    private function generateMoments($orderDetail){
        $project_data = $orderDetail->project_data;
        $spec = array();
        if(@$project_data->material) $spec['type'] = "Material: ".$project_data->material;
        if(@$project_data->size) $spec['size'] = "Size: ".$project_data->size;
        if(@$project_data->sides) $spec['sides'] = "Sides: ".$project_data->sides;
        switch($project_data->type){
            case "canvas":
                if(@$project_data->qty) $spec['display_name'] = $project_data->qty.' x Canvas';
                if(@$project_data->canvas_type) $spec['type'] = "Material: ".$project_data->canvas_type;
                if(@$project_data->orientation) $spec['orientation'] = "Orientation: ".$project_data->orientation;
                if(@$project_data->size) $spec['size'] = "Size: ".$project_data->size;
                if(@$project_data->spine) $spec['spine'] = "Spine: ".$project_data->spine;
                break;
            case "totebag":
                $spec['display_name'] = "1 x Tote Bag";
                if(@$project_data->size) $spec['size'] = "Size: ".$project_data->size;
                break;
            case "magnet":
                if(@$project_data->size) $spec['size'] = "Quantity: ".$project_data->size;
                if(@$project_data->shape) $spec['shape'] = "Shape: ".$project_data->shape;
                break;
            case "pillow":
                if(@$project_data->size) $spec['size'] = "Size: ".$project_data->size;
                if(@$project_data->insert) $spec['insert'] = "Insert: ".($project_data->insert === true ? "With Insert":"Without Insert");
                break;
            case "photoprint":
                if(@$project_data->size) $spec['size'] = "Quantity: ".$project_data->size;
                if(@$project_data->cover) $spec['cover'] = "Cover: ".$project_data->cover;
                if(@$project_data->useWood) $spec['useWood'] = ($project_data->useWood === true ? "With Wood Block & Box":"Without Wood Block & Box");
                break;
            case "photobook":
                if(@$project_data->paper_type) $spec['paper_type'] = "Paper: ".$project_data->paper_type;
                break;
            case "gadget":
                if(@$project_data->size) $spec['size'] = "Size: ".$project_data->size;
                break;
            case "frameart":
                if(@$project_data->size) $spec['size'] = "Size: ".$project_data->size;
                if(@$project_data->orientation) $spec['orientation'] = "Orientation: ".$project_data->orientation;
                if(@$project_data->frame) $spec['frame'] = "Frame: ".$project_data->frame;
                if(@$project_data->layout) {
                    if($project_data->layout == 'layout_1'){
                        $layout = '100';
                    }elseif($project_data->layout == 'layout_2'){
                        $layout = '75';
                    }else{
                        $layout = '50';
                    }
                    $spec['layout'] = "Layout: ".$layout." %";
                }
                break;
        }
        return $spec;
    }

    private function generateV3($orderDetail){
        $product = @$orderDetail->project_data->prod?:"other";
        $options = @$orderDetail->project_data->options?: null;
        $properties = @$orderDetail->project_data->properties?: null;
        $spec = array();
        //business card & square card
        if (in_array($product, array('businesscard', 'squarecard'))) {
            if(@$options->quantity){
                $spec['quantity'] = $options->quantity;
                if ($product == 'businesscard') {
                    $spec['quantity'] .= " x " . (@$orderDetail->project_data->display_name ? $orderDetail->project_data->display_name : "Business Card");
                } elseif ($product == 'squarecard') {
                    $spec['quantity'] .= " x " . (@$orderDetail->project_data->display_name ? $orderDetail->project_data->display_name : "Square Card");
                }
            }
            if(@$options->size) $spec['size'] = "Size: " . $options->size;
            if(@$options->sides) $spec['sides'] = "Sides: " . $options->sides;
            if(@$orderDetail->project_data->properties->Kertas){
                $spec['paper'] = "Papertype: " .$this->paperType($orderDetail->project_data->properties->Kertas);
            }elseif(@$options->paper){
                $spec['paper'] = "Papertype: " .$this->paperType($options->paper);
            }
            if ($product == 'businesscard') {
                $spec['finishing'] = "Finishing: " . (@$options->finishing ? $options->finishing : "");
                $spec['finish'] = "Cornertype: " . (@$options->finish ? $options->finish : "");
                $spec['laminate'] = "Lamination: " . (@$options->laminate ? $options->laminate : "");
            }
            if (@$options->speed) {
                if (strpos($options->speed, 'fast') !== false) $spec['speed'] = "Speed: Fast";
                if (strpos($options->speed, 'same') !== false) $spec['speed'] = "Speed: Same Day";
                if (strpos($options->speed, 'standar') !== false) $spec['speed'] = "Speed: Standar";
            }
        }

        //brochure
        if (in_array($product, array('brochure'))) {
            if(@$options->quantity){
                $spec['quantity'] = $options->quantity;
                $spec['quantity'] .= " x " . (@$orderDetail->project_data->display_name ? $orderDetail->project_data->display_name : "Brochure");
            }
            if(@$options->size) $spec['size'] = "Size: " . $options->size;
            if(@$options->sides) $spec['sides'] = "Sides: " . $options->sides;
            if(@$orderDetail->project_data->properties->Kertas){
                $spec['paper'] = "Papertype: " .$this->paperType($orderDetail->project_data->properties->Kertas);
            }elseif(@$options->paper){
                $spec['paper'] = "Papertype: " .$this->paperType($options->paper);
            }
            if (@$options->finish) {
                if ($options->finish == 2) $spec['finish'] = "Folding: Bifold";
                if ($options->finish == 'z_fold') $spec['finish'] = "Folding: Trifold - Z fold";
                if ($options->finish == 'u_fold') $spec['finish'] = "Folding: Trifold - U fold";
            }
            if (@$options->speed) {
                if (strpos($options->speed, 'fast') !== false) $spec['speed'] = "Speed: Fast";
                if (strpos($options->speed, 'same') !== false) $spec['speed'] = "Speed: Same Day";
                if (strpos($options->speed, 'standar') !== false) $spec['speed'] = "Speed: Standar";
            }
        }

        //calendar
        if (in_array($product, array('calendar'))) {
            if(@$options->quantity){
                $spec['quantity'] = $options->quantity;
                $spec['quantity'] .= " x " . (@$orderDetail->project_data->display_name ? $orderDetail->project_data->display_name : "Calendar");
            }
            /*if(@$options->size){
                if($options->size == 'wall') $spec['size'] = 'Side: 1 (side)';
                if($options->size == 'desk') $spec['size'] = 'Side: 2 (sides)';
            }*/
            if(@$orderDetail->project_data->properties->Kertas){
                $spec['paper'] = "Papertype: " .$this->paperType($orderDetail->project_data->properties->Kertas);
            }elseif(@$options->paper){
                $spec['paper'] = "Papertype: " .$this->paperType($options->paper);
            }
            $spec['laminate'] = "Lamination: " . (@$options->laminate ? $options->laminate : "");
            if (@$options->size) {
                $spec['size'] = "Type: " . $options->size;
                if($options->size == 'desk'){
                    $spec['board'] = "Board : Board Local 40 + Linen Hitam";
                }
            }
            if (@$options->sheet) $spec['sheet'] = "Sheets: " . $options->sheet;
            if (@$options->format) $spec['format'] = "Format: " . $options->format;
            if (@$options->spiral) $spec['spiral'] = "Spiral Color: ".$this->specFilter($options->spiral);
            if (@$options->package) $spec['package'] = "Packaging: " . $options->package;
            if (@$options->speed) {
                if (strpos($options->speed, 'fast') !== false) $spec['speed'] = "Speed: Fast";
                if (strpos($options->speed, 'same') !== false) $spec['speed'] = "Speed: Same Day";
                if (strpos($options->speed, 'standar') !== false) $spec['speed'] = "Speed: Standar";
            }
            if (@$orderDetail->project_data->design_file) {
                $idx = 0;
                $spec['design_file'] = "";
                foreach ($orderDetail->project_data->design_file as $key => $val) {
                    if ($key === 'email') {
                        $spec['design_file'] .= "File sent: By email";
                    } else {
                        if ($idx == 0) {
                            $spec['design_file'] .= "File sent: <br/>";
                        }
                        $spec['design_file'] .= "<a>" . substr($val, 0, 29) . "</a><br/>";
                    }
                    $idx++;
                }
            }
        }

        //flyer,letterhead,poster,envelope
        if (in_array($product, array('flyer', 'letterhead', 'poster', 'envelope'))) {
            if(@$options->quantity){
                $spec['quantity'] = $options->quantity;
                $spec['quantity'] .= " x " . (@$orderDetail->project_data->display_name ? $orderDetail->project_data->display_name : "Calendar");
            }
            if (@$options->size) $spec['size'] = "Size: " . $options->size;
            if (@$options->sides) $spec['sides'] = "Sides: " . $options->sides;
            if(@$orderDetail->project_data->properties->Kertas){
                $spec['paper'] = "Papertype: " .$this->paperType($orderDetail->project_data->properties->Kertas);
            }elseif(@$options->paper){
                $spec['paper'] = "Papertype: " .$this->paperType($options->paper);
            }
            if (@$options->speed) {
                if (strpos($options->speed, 'fast') !== false) $spec['speed'] = "Speed: Fast";
                if (strpos($options->speed, 'same') !== false) $spec['speed'] = "Speed: Same Day";
                if (strpos($options->speed, 'standar') !== false) $spec['speed'] = "Speed: Standar";
            }
            if (@$orderDetail->project_data->design_file) {
                $idx = 0;
                $spec['design_file'] = "";
                foreach ($orderDetail->project_data->design_file as $key => $val) {
                    if ($key === 'email') {
                        $spec['design_file'] .= "File sent: By email";
                    } else {
                        if ($idx == 0) {
                            $spec['design_file'] .= "File sent: <br/>";
                        }
                        $spec['design_file'] .= "<a>" . substr($val, 0, 29) . "</a><br/>";
                    }
                    $idx++;
                }
            }
        }

        //tshirt,poloshirt
        if(in_array($product, array('tshirt','poloshirt'))) {
            if (@$options->front) $spec['front'] = "Front: " . ucwords($options->front);
            if (@$options->back) $spec['back'] = "Back: " . ucwords($options->back);
            if (@$options->left) $spec['left'] = "Left Arm: " . ucwords($options->left);
            if (@$options->right) $spec['right'] = "Right Arm: " . ucwords($options->right);
            if (@$options->nlogo) $spec['nlogo'] = "Near Colar: " . ucwords($options->nlogo);
            if($product == 'poloshirt'){
                if (@$options->color) $spec['color'] = "Color: " . ucwords($options->color);
            }
            if (@$options->speed) {
                if (strpos($options->speed, 'sticker') !== false) $spec['speed'] = "Speed: Fast, Sticker";
                if (strpos($options->speed, 'sablon') !== false) $spec['speed'] = "Speed: Standar, Sablon";
            }
            if (@$options->qtySizeMap){
                $spec['qtySizeMap'] = "Quantity: <br/>";
                foreach($options->qtySizeMap as $key=>$val){
                    if(!is_array($val)){
                        $val = (array) $val;
                    }
                    if($val['qty'] != 0){
                        $spec['qtySizeMap'] .= (@$val['color']?ucwords($val['color']):"")." ".(@$val['size']?$val['size'].",":"")." ".(@$val['qty']?:"")."<br/>";
                    }
                }
            }
            if (@$orderDetail->project_data->design_file) {
                $idx = 0;
                $spec['design_file'] = "";
                foreach ($orderDetail->project_data->design_file as $key => $val) {
                    if ($key === 'email') {
                        $spec['design_file'] .= "File sent: By email";
                    } else {
                        if ($idx == 0) {
                            $spec['design_file'] .= "File sent: <br/>";
                        }
                        $spec['design_file'] .= "<a>" . substr($val, 0, 29) . "</a><br/>";
                    }
                    $idx++;
                }
            }
        }

        //banner,xbanner,rollupbanner,tripodbanner,eventbackwall,eventdesk,popuptable,popupstand,canvastotebag,spunboundtotebag,spunbond_tote,greetingcard,thankyoucard,voucher,stampcard,companyfolder,sticker,canvas_tote,loyaltycard
        if(in_array($product, array('banner','xbanner','rollupbanner','tripodbanner','eventbackwall','eventdesk','popuptable','popupstand','canvastotebag','spunboundtotebag','spunbond_tote','greetingcard','thankyoucard','voucher','stampcard','companyfolder','sticker','canvas_tote','loyaltycard'))){
            if($product == 'voucher'){
                if (@$options->quantity) {
                    $spec['quantity'] = $options->quantity." x ".(@$orderDetail->project_data->display_name?$orderDetail->project_data->display_name:$orderDetail->project_data->prod);
                    if(@$options->size && $options->size == '20x7') $spec['quantity'] .= "book (".$options->quantity." sheet)";
                }
            }else{
                if (@$options->quantity) $spec['quantity'] = $options->quantity." x ".(@$orderDetail->project_data->display_name?$orderDetail->project_data->display_name:$orderDetail->project_data->prod);
            }
            if (@$options->printopt) $spec['printopt'] = "Option: ".$this->specFilter($options->printopt);
            if (@$options->size) $spec['size'] = "Size: ".$this->specFilter($options->size);
            if (@$options->sides) $spec['sides'] = "Sides: ".$this->specFilter($options->sides). " (".($options->sides == 2? "Two" : "One").")";
            if(@$options->paper){
                if(@$orderDetail->project_data->properties->Kertas){
                    $spec['paper'] = "Material: " . $this->paperType($orderDetail->project_data->properties->Kertas);
                }else{
                    $spec['paper'] = "Material: " . $this->paperType($options->paper);
                }
            }
            if (@$options->material) $spec['material'] = "Material: ".$options->material;
            if (@$options->shape) $spec['shape'] = "Shape: ".$this->specFilter($options->shape);
            if (@$options->flap) $spec['flap'] = "Flap: ".$this->specFilter($options->flap);
            if (@$options->laminate && $options->laminate != 'none') $spec['laminate'] = "Laminasi: ".$options->laminate;
            if (@$options->finish) $spec['finish'] = "Finishing: ".$options->finish;
            if(@$options->finishing){
                $spec['finishing'] = "";
                if(is_array($options->finishing)){
                    foreach($options->finishing as $val){
                        $spec['finishing'] .= $val. "<br/>";
                    }
                }else{
                    $spec['finishing'] .= $options->finishing;
                }
            }
            if (@$options->speed) {
                if (strpos($options->speed, 'fast') !== false) $spec['speed'] = "Speed: Fast";
                if (strpos($options->speed, 'same') !== false) $spec['speed'] = "Speed: Same Day";
                if (strpos($options->speed, 'standar') !== false) $spec['speed'] = "Speed: Standar";
            }
            if (@$orderDetail->project_data->design_file) {
                $idx = 0;
                $spec['design_file'] = "";
                foreach ($orderDetail->project_data->design_file as $key => $val) {
                    if ($key === 'email') {
                        $spec['design_file'] .= "File sent: By email";
                    } else {
                        if ($idx == 0) {
                            $spec['design_file'] .= "File sent: <br/>";
                        }
                        $spec['design_file'] .= "<a>" . substr($val, 0, 29) . "</a><br/>";
                    }
                    $idx++;
                }
            }
            if(@$orderDetail->project_data && in_array($product, array('canvastotebag','spunboundtotebag','spunbond_tote')) && @$options->qtySizeMap){
                $spec['qtySizeMap'] = "Quantity: <br/>";
                foreach($options->qtySizeMap as $key=>$val){
                    if(!is_array($val)){
                        $val = (array) $val;
                    }
                    if($val['qty'] != 0){
                        $spec['qtySizeMap'] .= (@$val['color']?:"")." ".(@$val['size']?$val['size'].",":"")." ".(@$val['qty']?:"")."<br/>";
                    }
                }
            }
        }

        //spanduk
        if($product == "spanduk"){
            if (@$options->quantity) $spec['quantity'] = $options->quantity." x ".(@$orderDetail->project_data->display_name?$orderDetail->project_data->display_name:$orderDetail->project_data->prod);
            if(@$options->paper){
                if(@$orderDetail->project_data->properties->Kertas){
                    $spec['paper'] = "Material: " . $this->paperType($orderDetail->project_data->properties->Kertas);
                }else{
                    $spec['paper'] = "Material: " . $this->paperType($options->paper);
                }
            }
            if (@$options->material) $spec['material'] = "Material: ".$options->material;
            if (@$options->width && $options->width > 0) $spec['width'] = "Width: ".$options->width. " cm";
            if (@$options->height && $options->height > 0) $spec['height'] = "Height: ".$options->height. " cm";
            if (@$options->size_index && $options->size_index != 'custom') $spec['size_index'] = "Size Index: ".$options->size_index. " cm";
            if (@$options->speed) {
                if (strpos($options->speed, 'fast') !== false) $spec['speed'] = "Speed: Fast";
                if (strpos($options->speed, 'same') !== false) $spec['speed'] = "Speed: Same Day";
                if (strpos($options->speed, 'standar') !== false) $spec['speed'] = "Speed: Standar";
            }
            if (@$orderDetail->project_data->design_file) {
                $idx = 0;
                $spec['design_file'] = "";
                foreach ($orderDetail->project_data->design_file as $key => $val) {
                    if ($key === 'email') {
                        $spec['design_file'] .= "File sent: By email";
                    } else {
                        if ($idx == 0) {
                            $spec['design_file'] .= "File sent: <br/>";
                        }
                        $spec['design_file'] .= "<a>" . substr($val, 0, 29) . "</a><br/>";
                    }
                    $idx++;
                }
            }
        }

        //others
        if(!in_array($product, array('spanduk','banner','xbanner','rollupbanner','tripodbanner','eventbackwall','eventdesk','popuptable','popupstand','canvastotebag','spunboundtotebag','spunbond_tote','greetingcard','thankyoucard','voucher','stampcard','companyfolder','sticker','canvas_tote','loyaltycard','tshirt','poloshirt','flyer','letterhead','poster','envelope','calendar','brochure','businesscard','squarecard'))){
            if (@$product) $spec['prod'] = "Product: ".$this->specFilter($product);
            if (@$properties) {
                $spec['properties'] = '';
                if(is_object($properties)){
                    $properties = (array) $properties;
                }
                if(is_array($properties)) {
                    foreach ($properties as $key => $val) {
                        if (is_array($val)) {
                            foreach ($val as $key2 => $val2) {
                                $spec['properties'] .= $this->specFilter($key) . " : " . $this->specFilter($val2)."<br/>";
                            }
                        } else {
                            $spec['properties'] .= $this->specFilter($key) . " : " . $this->specFilter($val)."<br/>";
                        }
                    }
                }else{
                    $spec['properties'] .= $properties;
                }
            }else{
                $spec['properties'] = '';
                foreach($options as $key=>$val){
                    if(is_array($val)){
                        foreach($val as $key2=>$val2){
                            $spec['properties'].= $this->specFilter($key)." : ".$this->specFilter($val2)."<br/>";
                        }
                    }elseif(!in_array($key, array('pro_product_id','pro_product_title','quantity','prod'))){
                        $spec['properties'].= $this->specFilter($key)." : ".$this->specFilter($val)."<br/>";
                    }
                }
            }
            if (@$orderDetail->project_data->design_file) {
                $spec['design_file'] = "";
                foreach ($orderDetail->project_data->design_file as $key => $val) {
                    $spec['design_file'] .= "<a href='".$this->createUrl($val)."' style='word-wrap: break-word;'>".substr($val,0,30)."</a><br/>";
                }
            }
        }

        return $spec;
    }

    private function createUrl($value){
        $temp = parse_url($value);
        $url = $value;
        if(isset($temp['scheme']) && $temp['scheme'] != 'https'){
            $url = "http://".$url;
        }
        return $url;
    }

    private function paperType($paper){
        $desc = ucwords($paper);
        switch(strtolower($paper)){
            case "ac260": $desc = 'Art Carton 260 gsm'; break;
            case "ac310": $desc = 'Art Carton 310 gsm'; break;
            case "cs240": $desc = 'Constellation Snow 240 gsm'; break;
            case "s270": $desc = 'Splendorgel 270 gsm'; break;
            case "esb216": $desc = 'Everyday smooth bright 216 gsm'; break;
            case "mp150": $desc = 'Matte Paper 150 gsm'; break;
            case "ap120": $desc = 'Art Paper 120 gsm'; break;
            case "satin216": $desc = 'Digital Via Satin 216 gsm'; break;
            case "mp120": $desc = 'Matte Paper 120 gsm'; break;
            case "srat238": $desc = 'Strathmore Writing 238 gsm'; break;
            case "everyday148": $desc = 'Everyday smooth bright 148 gsm'; break;
            case "splendor160": $desc = 'Splendorgel Extra White 160 gsm'; break;
            case "hvs100": $desc = 'HVS 100 gsm'; break;
            case "via148": $desc = 'Via Linen bright white 104 gsm'; break;
            case "ff440": $desc = 'FF Korea 440gr'; break;
            case "ff340": $desc = 'FF China 340gr'; break;
            case "sww238": $desc = 'ST WRITING WOVE (ultimate white] 238 gsm'; break;
            case "dpro190": $desc = 'DIGITAL PROPHOTO (WHITE] 190 gsm'; break;
            case "dpro260": $desc = 'DIGITAL PROPHOTO (WHITE] 260 gsm'; break;
            case "corolla240": $desc = 'COROLLA PENTAGRAM 240 gsm'; break;
            case "tinto250": $desc = 'TINTORETTO NEVE 250 gsm'; break;
            case "ombianco250": $desc = 'OLD MILL BIANCO 250 gsm'; break;
            case "acqbianco240": $desc = 'ACQUERELLO BIANCO 240 gsm'; break;
            case "fpolardawn125": $desc = 'FANCY POLAR DAWN 125 gsm'; break;
            case "fpolardawn300": $desc = 'FANCY POLAR DAWN 300 gsm'; break;
            case "fmicegold120": $desc = 'FANCY METALIC ICE GOLD 120 gsm'; break;
            case "fmicegold300": $desc = 'FANCY METALIC ICE GOLD 300 gsm'; break;
            case "fusion200": $desc = 'FUSION 200 gsm'; break;
            case "fusion300": $desc = 'FUSION 300 gsm'; break;
            case "feggshell148": $desc = 'FANCY EGGSHEL 148 gsm'; break;
            case "feggshell216": $desc = 'FANCY EGGSHEL 216 gsm'; break;
            case "feggshell270": $desc = 'FANCY EGGSHEL (SUPERFINE] 270 gsm'; break;
            case "vlinen104": $desc = 'VIA LINEN 104 gsm BRIGHT WHITE'; break;
            case "vlinen216": $desc = 'VIA LINEN 216 gsm BRIGHT WHITE'; break;
            case "vlinen298": $desc = 'VIA LINEN 298 gsm BRIGHT WHITE'; break;
            case "vfelt118": $desc = 'VIA FELT 118 gsm BRIGHT WHITE'; break;
            case "vfelt216": $desc = 'VIA FELT 216 gsm BRIGHT WHITE'; break;
            case "vfelt298": $desc = 'VIA FELT 298 gsm BRIGHT WHITE'; break;
            case "splendorgel160": $desc = 'SPLENDORGEL EXTRA WHITE 160 gsm'; break;
            case "splendorgel230": $desc = 'SPLENDORGEL EXTRA WHITE 230 gsm'; break;
            case "splendorgel270": $desc = 'SPLENDORGEL EXTRA WHITE 270 gsm'; break;
            case "freelife130": $desc = 'SIMBOL FREELIFE RASTER 130 gsm'; break;
            case "freelife250": $desc = 'SIMBOL FREELIFE RASTER 250 gsm'; break;
            case "constelation170": $desc = 'CONSTELATION SNOW 170 gsm'; break;
            case "constelation240": $desc = 'CONSTELATION SNOW 240 gsm'; break;
            case "feveryday270": $desc = 'FANCY EVERYDAY VELLUM (WHITE] 270 gsm'; break;
            case "vsatin148": $desc = 'VIA SATIN BRIGHT WHITE 148 gsm'; break;
            case "vsatin216": $desc = 'VIA SATIN BRIGHT WHITE 216 gsm'; break;
            case "esb148": $desc = 'EVERYDAY SMOOTH BRIGHT 148 gsm'; break;
            case "esb270": $desc = 'EVERYDAY SMOOTH BRIGHT 270 gsm'; break;
            case "ap150": $desc = 'ART PAPER 150 gsm'; break;
            case "ac190": $desc = 'ART CARTON 190 gsm'; break;
            case "ac210": $desc = 'ART CARTON 210 gsm'; break;
            case "ff280": $desc = 'FF China 280 GSM'; break;
            case "vsticker": $desc = 'Vinyl Sticker + Polyfoam'; break;
            case "fe148": $desc = 'Fancy Eggshell 148 GSM'; break;
            case "co170": $desc = 'Constellation Snow 170 GSM'; break;
            case "sg230": $desc = 'Splendorgel 230 GSM'; break;
            case "vf298": $desc = 'Via Felt 298 GSM'; break;
            case "fe216": $desc = 'Fancy Eggshell 216 gsm'; break;
        }
        return $desc;
    }

    private function specFilter($spec){
        $custSpec = ucwords($spec);
        if (empty($spec)){
            $custSpec = '';
        } else if($spec == 'rollup60' || $spec == 'xbanner60') {
            $custSpec = '60 x 160 cm';
        } else if($spec == 'xbanner25') {
            $custSpec = '25 x 45 cm';
        } else if($spec == 'rollup85') {
            $custSpec = '85 x 200 cm';
        } else if($spec == 'printonly') {
            $custSpec = 'Print Only';
        } else if($spec == 'printleg') {
            $custSpec = 'Print + Leg';
        } else if($spec == 'wallwhite' || $spec == 'deskwhite') {
            $custSpec = 'White';
        } else if($spec == 'wallblack' || $spec == 'deskblack') {
            $custSpec = 'Black';
        }
        return $custSpec;
    }
}
