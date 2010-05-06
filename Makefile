SASS		= sass
STYLESHEETS	= public/style.css public/backend.css

all : stylesheets

.PHONY : clean stylesheets

stylesheets : $(STYLESHEETS)

%.css : %.sass
	$(SASS) $< $@

clean :
	rm public/*.css
