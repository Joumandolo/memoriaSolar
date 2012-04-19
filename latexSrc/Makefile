SHELL=/bin/bash
DOC = memoria
CHAP = {1-agradecimientos,2-resumen,3-abstract,4-Introduccion,5-Problema,6-Desarrollo,7-Conclusiones,8-Anexos}
INS = fichaInscripcion

#BIN=bin
#FLAGS=-output-directory $(BIN)
#DEPENDENCIES=$(G)/$(G)_$(T).tex

all:
	rubber --pdf $(DOC)
ins:
	rubber --pdf $(INS)
clean:
	@echo "Limpiando..."
	@rm -f $(DOC).{aux,toc,log,lof,tmp,dvi,idx,ilg,ind,bbl,blg,out,nav,snm,fb.aux,gls.aux,bcf,run.xml,-blx.bib} .my_log
	@rm -f memoria-blx.bib .my_log
	@rm -f chapters/$(CHAP).aux .my_log
	@rm -f $(INS).{aux,toc,log,lof,tmp,dvi,idx,ilg,ind,bbl,blg,out,nav,snm,fb.aux,gls.aux,bcf,run.xml} .my_log
distclean:
	rubber --pdf --clean $(DOC)
	rubber --pdf --clean $(INS)

.PHONY: all clean distclean
