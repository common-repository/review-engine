<div id="display-button" style="display: none;">
    <h3><?php _e('Button Options'); ?></h3>
    <form action="#" method="post" id="display-button-form">
        <div class="form-group row">
            <label for="product" class="col-sm-3 col-form-label"><?php _e('Product'); ?></label>
            <div class="col-sm-9">
                <a href="#" target="_blank" class="name"></a>
            </div>
        </div><!-- .form-group -->

        <div class="form-group row form-group-tracking-id">
            <label for="tracking-id" class="col-sm-3 col-form-label"><?php _e('Tracking ID'); ?></label>
            <div class="col-sm-9">
                <select name="tracking-id" id="tracking-id" class="form-control">
                    <option value=""><?php _e('None'); ?></option>
                </select>
            </div>
        </div><!-- .form-group -->

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" data-toggle="tab" href="#general"><?php _e('General'); ?></a>
                <a class="nav-item nav-link" data-toggle="tab" href="#style"><?php _e('Style'); ?></a>
            </div>
        </nav>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="general">
                <div class="form-group row">
                    <label for="btn-text" class="col-sm-3 col-form-label"><?php _e('Text'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="btn-text" id="btn-text" class="form-control">
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row">
                    <label for="btn-icon" class="col-sm-3 col-form-label"><?php _e('Icon'); ?></label>
                    <div class="col-sm-9">
                        <input type="hidden" name="btn-icon" id="btn-icon" class="form-control" placeholder="<?php _e('Select Icon'); ?>">
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row">
                    <label for="icon-position" class="col-sm-3 col-form-label"><?php _e('Icon Position'); ?></label>
                    <div class="col-sm-9">
                        <select name="icon-position" id="icon-position" class="form-control">
                            <option value="before"><?php _e('Before Text'); ?></option>
                            <option value="after"><?php _e('After Text'); ?></option>
                            <option value="hide"><?php _e('Hide'); ?></option>
                        </select>
                    </div>
                </div><!-- .form-group -->
            </div><!-- .tab-pane -->

            <div class="tab-pane fade" id="style">
                <div class="form-group row form-group-btn-style">
                    <label for="btn-style" class="col-sm-3 col-form-label"><?php _e('Style'); ?></label>
                    <div class="col-sm-9">
                        <select name="btn-style" id="btn-style" class="form-control">
                            <option value="flat"><?php _e('Flat'); ?></option>
                            <option value="gradient"><?php _e('Gradient'); ?></option>
                            <option value="transparent"><?php _e('Transparent'); ?></option>
                        </select>
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-size">
                    <label for="size-s" class="col-sm-3 col-form-label"><?php _e('Size'); ?></label>
                    <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                            <input type="radio" name="size" id="size-s" class="form-check-input" value="s" checked="">
                            <label for="size-s" class="form-check-label"><?php _e('S'); ?></label>
                        </div><!-- .form-check -->

                        <div class="form-check form-check-inline">
                            <input type="radio" name="size" id="size-m" class="form-check-input" value="m">
                            <label for="size-m" class="form-check-label"><?php _e('M'); ?></label>
                        </div><!-- .form-check -->

                        <div class="form-check form-check-inline">
                            <input type="radio" name="size" id="size-l" class="form-check-input" value="l">
                            <label for="size-l" class="form-check-label"><?php _e('L'); ?></label>
                        </div><!-- .form-check -->

                        <div class="form-check form-check-inline">
                            <input type="radio" name="size" id="size-xl" class="form-check-input" value="xl">
                            <label for="size-xl" class="form-check-label"><?php _e('XL'); ?></label>
                        </div><!-- .form-check -->

                        <div class="form-check form-check-inline">
                            <input type="radio" name="size" id="size-xxl" class="form-check-input" value="xxl">
                            <label for="size-xxl" class="form-check-label"><?php _e('XXL'); ?></label>
                        </div><!-- .form-check -->
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-width">
                    <label for="width" class="col-sm-3 col-form-label"><?php _e('Width'); ?></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="number" name="width" id="width" min="10" max="1000" step="0.1" class="form-control">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php _e('px'); ?></span>
                            </div>
                        </div><!-- .input-group -->
                    </div>
                    <div class="col-sm-4">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="fullwidth" id="fullwidth" class="form-check-input" value="1">
                            <label for="fullwidth" class="form-check-label"><?php _e('Full width'); ?></label>
                        </div><!-- .form-check -->
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-bg-color">
                    <label for="bg-color" class="col-sm-3 col-form-label"><?php _e('Background Color'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="bg-color" id="bg-color" class="form-control reviewengine-colorpicker" value="#238ae6">
                    </div>
                </div><!-- .form-group -->
                
                <div class="form-group row form-group-secondary-bg-color" style="display: none;">
                    <label for="secondary-bg-color" class="col-sm-3 col-form-label"><?php _e('Secondary Background Color'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="secondary-bg-color" id="secondary-bg-color" class="form-control reviewengine-colorpicker" value="#41a8ff">
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-bg-hover-color">
                    <label for="bg-hover-color" class="col-sm-3 col-form-label"><?php _e('Background Hover Color'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="bg-hover-color" id="bg-hover-color" class="form-control reviewengine-colorpicker" value="#238ae6">
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-secondary-bg-hover-color" style="display: none;">
                    <label for="secondary-bg-hover-color" class="col-sm-3 col-form-label"><?php _e('Secondary Background Hover Color'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="secondary-bg-hover-color" id="secondary-bg-hover-color" class="form-control reviewengine-colorpicker" value="#41a8ff">
                    </div>
                </div><!-- .form-group -->
                
                <div class="form-group row form-group-text-color">
                    <label for="text-color" class="col-sm-3 col-form-label"><?php _e('Text Color'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="text-color" id="text-color" class="form-control reviewengine-colorpicker" value="#ffffff">
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-text-color-hover">
                    <label for="text-hover-color" class="col-sm-3 col-form-label"><?php _e('Text Hover Color'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="text-hover-color" id="text-hover-color" class="form-control reviewengine-colorpicker" value="#ffffff">
                    </div>
                </div><!-- .form-group -->
                
                <div class="form-group row form-group-border">
                    <label for="border-top" class="col-sm-3 col-form-label"><?php _e('Border Size'); ?></label>
                    <div class="col-sm-9">
                        <div class="input-complex">
                            <label for="border-top"><?php _e('Top'); ?></label>
                            <input type="number" name="border-top" id="border-top" min="0" step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>

                            <label for="border-right"><?php _e('Right'); ?></label>
                            <input type="number" name="border-right" id="border-right" min="0" step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>

                            <label for="border-bottom"><?php _e('Bottom'); ?></label>
                            <input type="number" name="border-bottom" id="border-bottom" min="0" step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>

                            <label for="border-left"><?php _e('Left'); ?></label>
                            <input type="number" name="border-left" id="border-left" min="0" step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>
                        </div><!-- .input-complex -->
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-border-style">
                    <label for="border-style" class="col-sm-3 col-form-label"><?php _e('Border Style'); ?></label>
                    <div class="col-sm-9">
                        <select name="border-style" id="border-style" class="form-control">
                            <option value="solid" selected=""><?php _e('Solid'); ?></option>
                            <option value="dashed"><?php _e('Dashed'); ?></option>
                            <option value="dotted"><?php _e('Dotted'); ?></option>
                        </select>
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-border-color">
                    <label for="border-color" class="col-sm-3 col-form-label"><?php _e('Border Color'); ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="border-color" id="border-color" class="form-control reviewengine-colorpicker" value="#238ae6">
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-radius">
                    <label for="radius-top" class="col-sm-3 col-form-label"><?php _e('Round Corners'); ?></label>
                    <div class="col-sm-9">
                        <div class="input-complex">
                            <label for="radius-top"><?php _e('T. Left'); ?></label>
                            <input type="number" name="radius-tleft" id="radius-top" min="0" step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>

                            <label for="radius-right"><?php _e('T. Right'); ?></label>
                            <input type="number" name="radius-tright" id="radius-right" min="0" step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>

                            <label for="radius-bottom"><?php _e('B. Left'); ?></label>
                            <input type="number" name="radius-bleft" id="radius-bottom" min="0"step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>

                            <label for="radius-left"><?php _e('B. Right'); ?></label>
                            <input type="number" name="radius-bright" id="radius-left" min="0" step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>
                        </div><!-- .input-complex -->
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-padding">
                    <label for="padding-top" class="col-sm-3 col-form-label"><?php _e('Padding'); ?></label>
                    <div class="col-sm-9">
                        <div class="input-complex">
                            <label for="padding-top"><?php _e('Top'); ?></label>
                            <input type="number" name="padding-top" id="padding-top" min="0" step="0.1" placeholder="12">
                            <span><?php _e('px'); ?></span>

                            <label for="padding-right"><?php _e('Right'); ?></label>
                            <input type="number" name="padding-right" id="padding-right" min="0" step="0.1" placeholder="15">
                            <span><?php _e('px'); ?></span>

                            <label for="padding-bottom"><?php _e('Bottom'); ?></label>
                            <input type="number" name="padding-bottom" id="padding-bottom" min="0" step="0.1" placeholder="12">
                            <span><?php _e('px'); ?></span>

                            <label for="padding-left"><?php _e('Left'); ?></label>
                            <input type="number" name="padding-left" id="padding-left" min="0" step="0.1" placeholder="15">
                            <span><?php _e('px'); ?></span>
                        </div><!-- .input-complex -->
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-margin">
                    <label for="margin-top" class="col-sm-3 col-form-label"><?php _e('Margin'); ?></label>
                    <div class="col-sm-9">
                        <div class="input-complex">
                            <label for="margin-top"><?php _e('Top'); ?></label>
                            <input type="number" name="margin-top" id="margin-top" min="0" step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>

                            <label for="margin-right"><?php _e('Right'); ?></label>
                            <input type="number" name="margin-right" id="margin-right" min="0"step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>

                            <label for="margin-bottom"><?php _e('Bottom'); ?></label>
                            <input type="number" name="margin-bottom" id="margin-bottom" min="0" step="0.1" placeholder="20">
                            <span><?php _e('px'); ?></span>

                            <label for="margin-left"><?php _e('Left'); ?></label>
                            <input type="number" name="margin-left" id="margin-left" min="0" step="0.1" placeholder="0">
                            <span><?php _e('px'); ?></span>
                        </div><!-- .input-complex -->
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-alignment">
                    <label for="alignment" class="col-sm-3 col-form-label"><?php _e('Alignment'); ?></label>
                    <div class="col-sm-9">
                        <select name="alignment" id="alignment" class="form-control">
                            <option value="left" selected=""><?php _e('Left'); ?></option>
                            <option value="center"><?php _e('Center'); ?></option>
                            <option value="right"><?php _e('Right'); ?></option>
                        </select>
                    </div>
                </div><!-- .form-group -->
                
                <div class="form-group row form-group-font-size">
                    <label for="font-size" class="col-sm-3 col-form-label"><?php _e('Font Size'); ?></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="number" name="font-size" id="font-size" min="10" max="100" step="0.1" placeholder="18" class="form-control">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php _e('px'); ?></span>
                            </div>
                        </div><!-- .input-group -->
                    </div>
                    <div class="col-sm-4">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="font-bold" id="font-bold" class="form-check-input" value="1">
                            <label for="font-bold" class="form-check-label"><?php _e('Bold'); ?></label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="font-italic" id="font-italic" class="form-check-input" value="1">
                            <label for="font-italic" class="form-check-label"><?php _e('Italic'); ?></label>
                        </div>
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-line-height">
                    <label for="line-height" class="col-sm-3 col-form-label"><?php _e('Line Height'); ?></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="number" name="line-height" id="line-height" min="10" max="100" step="0.1" placeholder="21" class="form-control">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php _e('px'); ?></span>
                            </div>
                        </div><!-- .input-group -->
                    </div>
                </div><!-- .form-group -->

                <div class="form-group row form-group-letter-spacing">
                    <label for="letter-spacing" class="col-sm-3 col-form-label"><?php _e('Letter Spacing'); ?></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="number" name="letter-spacing" id="letter-spacing" step="0.01" class="form-control">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php _e('px'); ?></span>
                            </div>
                        </div><!-- .input-group -->
                    </div>
                </div><!-- .form-group -->
            </div><!-- .tab-pane -->
        </div>

        <div class="form-button">
            <div class="row">
                <div class="col-md-9">
                    <label class="col-form-label"><?php _e('Preview'); ?></label>
                    <div class="reviewengine-btn-wrap">
                        <a href="#" class="reviewengine-btn reviewengine-btn-s reviewengine-btn-flat" target="_blank">
                            <span class="reviewengine-btn-text"><?php _e('You Awesome Button'); ?></span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary button-insert-button"><?php _e('Insert Button'); ?></button>
                    <button type="button" class="btn btn-default button-display-cancel"><?php _e('Cancel'); ?></button>
                </div>
            </div><!-- .row -->
        </div>
    </form>
</div><!-- #display-button -->