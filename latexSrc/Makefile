SHELL=/bin/bash
DOC = memoria
CHAP = {1-agradecimientos,2-resumen,3-abstract,4-Introduccion,5-Problema,6-Desarrollo,7-Conclusiones,8-Anexos}
INS = fichaInscripcion

BIN=bin
FLAGS=-output-directory $(BIN)
#DEPENDENCIES=$(G)/$(G)_$(T).tex

all:
	@pdflatex $(DOC).tex
	@bibtex $(DOC).gls.aux
	@bibtex $(DOC).aux
	@pdflatex $(DOC).tex
	pdflatex $(DOC).tex > .$(DOC).log
	@pdflatex $(INS).tex
	pdflatex $(INS).tex > .$(INS).log	
mem:
	@pdflatex $(DOC).tex
	@bibtex $(DOC).gls.aux
	@bibtex $(DOC).aux
	@pdflatex $(DOC).tex
	pdflatex $(DOC).tex > .$(DOC).log
ins:
	@pdflatex $(INS).tex
	pdflatex $(INS).tex > .$(INS).log
clean:
	rm -f $(DOC).{aux,out,toc,lot,lof,gls.aux,gls.blg,gls.bbl,bbl,blg} $(DOC)-blx.bib
	rm -f $(INS).{aux,out,toc,lot,lof,gls.aux,gls.blg,gls.bbl} $(DOC)-blx.bib
	rm -f chapters/*.aux 
distclean:
	rm -f $(DOC).{aux,out,toc,lot,lof,gls.aux,gls.blg,gls.bbl,log,pdf,bbl,blg} $(DOC)-blx.bib
	rm -f $(INS).{aux,out,toc,lot,lof,gls.aux,gls.blg,gls.bbl,log,pdf} $(DOC)-blx.bib
	rm -f chapters/*.aux
	rm -f chapters/*.tex.bak
