<?php
foreach( $style as $button => $data ) :

    $style = 'flat';
    $size = 's';

    if( !empty( $data['btn-style'] ) ) {
        $style = $data['btn-style'];
    }

    if( !empty( $data['size'] ) ) {
        $size = $data['size'];
    }

    $width = isset( $data['width'] ) ? $data['width'] : '';
    $fullwidth = isset( $data['fullwidth'] ) ? $data['fullwidth'] : '';
    $bgColor = isset( $data['bg-color'] ) ? $data['bg-color'] : '';
    $bgHoverColor = isset( $data['bg-hover-color'] ) ? $data['bg-hover-color'] : '';
    $secondaryBgColor = isset( $data['secondary-bg-color'] ) ? $data['secondary-bg-color'] : '';
    $secondaryBgHoverColor = isset( $data['secondary-bg-hover-color'] ) ? $data['secondary-bg-hover-color'] : '';
    $textColor = isset( $data['text-color'] ) ? $data['text-color'] : '';
    $textHoverColor = isset( $data['text-hover-color'] ) ? $data['text-hover-color'] : '';
    $borderTop = isset( $data['border-top'] ) ? $data['border-top'] : '';
    $borderRight = isset( $data['border-right'] ) ? $data['border-right'] : '';
    $borderBottom = isset( $data['border-bottom'] ) ? $data['border-bottom'] : '';
    $borderLeft = isset( $data['border-left'] ) ? $data['border-left'] : '';
    $borderStyle = isset( $data['border-style'] ) ? $data['border-style'] : '';
    $borderColor = isset( $data['border-color'] ) ? $data['border-color'] : '';
    $radiusTopLeft = isset( $data['radius-tleft'] ) ? $data['radius-tleft'] : '';
    $radiusTopRight = isset( $data['radius-tright'] ) ? $data['radius-tright'] : '';
    $radiusBottomLeft = isset( $data['radius-bleft'] ) ? $data['radius-bleft'] : '';
    $radiusBottomRight = isset( $data['radius-bright'] ) ? $data['radius-bright'] : '';
    $paddingTop = isset( $data['padding-top'] ) ? $data['padding-top'] : '';
    $paddingRight = isset( $data['padding-right'] ) ? $data['padding-right'] : '';
    $paddingBottom = isset( $data['padding-bottom'] ) ? $data['padding-bottom'] : '';
    $paddingLeft = isset( $data['padding-left'] ) ? $data['padding-left'] : '';
    $marginTop = isset( $data['margin-top'] ) ? $data['margin-top'] : '';
    $marginRight = isset( $data['margin-right'] ) ? $data['margin-right'] : '';
    $marginBottom = isset( $data['margin-bottom'] ) ? $data['margin-bottom'] : '';
    $marginLeft = isset( $data['margin-left'] ) ? $data['margin-left'] : '';
    $alignment = isset( $data['alignment'] ) ? $data['alignment'] : '';
    $fontSize = isset( $data['font-size'] ) ? $data['font-size'] : '';
    $lineHeight = isset( $data['line-height'] ) ? $data['line-height'] : '';
    $letterSpacing = isset( $data['letter-spacing'] ) ? $data['letter-spacing'] : '';
    $fontBold = isset( $data['font-bold'] ) ? $data['font-bold'] : '';
    $fontItalic = isset( $data['font-italic'] ) ? $data['font-italic'] : '';
    ?>

    .reviewengine-btn-wrap.reviewengine-btn-wrap-<?php echo $button; ?> {
        <?php
        if( $alignment ) {
            echo "text-align: {$alignment};";
        }

        // margin
        if( $marginTop ) {
            echo "margin-top: {$marginTop}px;";
        }

        if( $marginRight ) {
            echo "margin-right: {$marginRight}px;";
        }

        if( $marginBottom ) {
            echo "margin-bottom: {$marginBottom}px;";
        }

        if( $marginLeft ) {
            echo "margin-left: {$marginLeft}px;";
        }
        // end margin
        ?>
    }

    .reviewengine-btn.reviewengine-btn-<?php echo $button; ?> {
        <?php
        // width
        if( $fullwidth == 1 ) {
            echo "width: 100%;";
        } elseif( !empty( $width ) ) {
            echo "width: {$width}px;";
        } else {
            echo "width: auto;";
        }
        // end width

        // background color
        if( $style == 'gradient' ) {
            if( !empty( $bgColor ) && !empty( $secondaryBgColor ) ) {
                echo "background: {$bgColor};";
                echo "background: -moz-linear-gradient(top,{$secondaryBgColor} 0%, {$bgColor} 100%);";
                echo "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,{$secondaryBgColor}), color-stop(100%,{$bgColor}));";
                echo "background: -webkit-linear-gradient(top,{$secondaryBgColor} 0%,{$bgColor} 100%);";
                echo "background: -o-linear-gradient(top,{$secondaryBgColor} 0%,{$bgColor} 100%);";
                echo "background: -ms-linear-gradient(top,{$secondaryBgColor} 0%,{$bgColor} 100%);";
                echo "background: linear-gradient(to bottom,{$secondaryBgColor} 0%,{$bgColor} 100%);";
                echo "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$secondaryBgColor}', endColorstr='{$bgColor}',GradientType=0 );";
            }
        } elseif( $style == 'transparent' ) {
            echo "background-color: transparent;";
        } elseif( !empty( $bgColor ) ) {
            echo "background-color: {$bgColor};";
        }
        // end background color

        // text color
        if( $textColor ) {
            echo "color: {$textColor};";
        }
        // end text color

        // border
        if( $borderTop ) {
            echo "border-top-width: {$borderTop}px;";
        }
        
        if( $borderRight ) {
            echo "border-right-width: {$borderRight}px;";
        }

        if( $borderBottom ) {
            echo "border-bottom-width: {$borderBottom}px;";
        }

        if( $borderLeft ) {
            echo "border-left-width: {$borderLeft}px;";
        }

        if( $borderStyle ) {
            echo "border-style: {$borderStyle};";
        }

        if( $borderColor ) {
            echo "border-color: {$borderColor};";
        }
        // end border

        // radius
        if( $radiusTopLeft ) {
            echo "border-top-left-radius: {$radiusTopLeft}px;";
        }

        if( $radiusTopRight ) {
            echo "border-top-right-radius: {$radiusTopRight}px;";
        }

        if( $radiusBottomLeft ) {
            echo "border-bottom-left-radius: {$radiusBottomLeft}px;";
        }

        if( $radiusBottomRight ) {
            echo "border-bottom-right-radius: {$radiusBottomRight}px;";
        }
        // end radius

        // padding
        if( $paddingTop ) {
            echo "padding-top: {$paddingTop}px;";
        }

        if( $paddingRight ) {
            echo "padding-right: {$paddingRight}px;";
        }

        if( $paddingBottom ) {
            echo "padding-bottom: {$paddingBottom}px;";
        }

        if( $paddingLeft ) {
            echo "padding-left: {$paddingLeft}px;";
        }
        // end padding

        // font
        if( $fontSize ) {
            echo "font-size: {$fontSize}px;";
        }

        if( $lineHeight ) {
            echo "line-height: {$lineHeight}px;";
        }

        if( $letterSpacing ) {
            echo "letter-spacing: {$letterSpacing}px;";
        }

        if( $fontBold == 1 )  {
            echo "font-weight: 700;";
        }

        if( $fontItalic == 1 )  {
            echo "font-style: italic;";
        }
        // end font
        ?>
    }

    .reviewengine-btn.reviewengine-btn-<?php echo $button; ?>:hover {
        <?php
        // background color
        if( $style == 'gradient' ) {
            if( !empty( $bgHoverColor ) && !empty( $secondaryBgHoverColor ) ) {
                echo "background: {$bgHoverColor};";
                echo "background: -moz-linear-gradient(top,{$secondaryBgHoverColor} 0%, {$bgHoverColor} 100%);";
                echo "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,{$secondaryBgHoverColor}), color-stop(100%,{$bgHoverColor}));";
                echo "background: -webkit-linear-gradient(top,{$secondaryBgHoverColor} 0%,{$bgHoverColor} 100%);";
                echo "background: -o-linear-gradient(top,{$secondaryBgHoverColor} 0%,{$bgHoverColor} 100%);";
                echo "background: -ms-linear-gradient(top,{$secondaryBgHoverColor} 0%,{$bgHoverColor} 100%);";
                echo "background: linear-gradient(to bottom,{$secondaryBgHoverColor} 0%,{$bgHoverColor} 100%);";
                echo "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$secondaryBgHoverColor}', endColorstr='{$bgHoverColor}',GradientType=0 );";
            }
        } elseif( !empty( $bgHoverColor ) ) {
            echo "background-color: {$bgHoverColor};";
        }
        // end background color

        // text color
        if( $textHoverColor ) {
            echo "color: {$textHoverColor};";
        }
        // end text color
        ?>
    }
    <?php
endforeach;