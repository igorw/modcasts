SASS		= sass

all: stylesheets

.PHONY: clean

stylesheets: public/style.css public/backend.css
	$(SASS) public/style.sass public/style.css
	$(SASS) public/backend.sass public/backend.css

clean:
	rm public/*.css
