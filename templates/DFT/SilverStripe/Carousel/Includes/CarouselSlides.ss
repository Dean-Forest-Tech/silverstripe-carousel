<% if $shouldAddCarouselRequireExtras %>
<% require css("https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css") %>
<% require css("dft/silverstripe-carousel: client/dist/styles/carousel.css") %>

<% require javascript("https://code.jquery.com/jquery-3.2.1.slim.min.js") %>
<% require javascript("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js") %>
<% require javascript("https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js") %>
<% end_if %>

<% if $ShowCarousel && $Slides.exists %>
    <div id="CarouselSlider" class="carousel slide" data-interval="{$Interval}" data-ride="carousel">

        <% if $ShowIndicators = 1 %>
            <ol class="carousel-indicators">
                <% loop $Slides %>
                    <li
                        data-target="#CarouselSlider"
                        data-slide-to="$Pos(0)"
                        <% if $First || $IsFirst %>class="active"<% end_if %>
                    ></li>
                <% end_loop %>
            </ol>
        <% end_if %>

        <div class="carousel-inner">
            <% loop $Slides %>
                <div class="carousel-item <% if $First || $IsFirst %>active<% end_if %>">
                    {$RenderedImage}
                    <% if Title %>
                        <div class="carousel-caption text-center d-block w-100 slide-content">
							<% if $Link.LinkURL %>
								<p class="h2"><strong>
                                    <a href="$Link.LinkURL" $Link.TargetAttr>
                                        $Title.RAW
                                    </a>
                                </strong></p>
							<% else %>
								<p class="h2"><strong>
                                    $Title.RAW
                                </strong></p>
							<% end_if %>
                        </div>
                    <% end_if %>
                </div>
            <% end_loop %>
        </div>

        <% if $ShowControls = 1 %>
            <a class="carousel-control-prev" href='#CarouselSlider' role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only"><%t Carousel.Prev "Prev" %></span>
            </a>
            <a class="carousel-control-next" href='#CarouselSlider' role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only"><%t Carousel.Next "Next" %></span>
            </a>
        <% end_if %>
    </div>
<% end_if %>
