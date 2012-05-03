SHELL=/bin/bash
DOC = memoria
CHAP = {1-agradecimientos,2-resumen,3-abstract,4-Introduccion,5-Problema,6-Desarrollo,7-Conclusiones,8-Anexos}
INS = fichaInscripcion

#BIN=bin
#FLAGS=-output-directory $(BIN)
#DEPENDENCIES=$(G)/$(G)_$(T).tex

all:
	rubber --pdf $(DOC)
	rubber --pdf $(INS)
mem:
	rubber --pdf $(DOC)
ins:
	rubber --pdf $(INS)
clean:
	rubber --clean $(DOC)
distclean:
	rubber --pdf --clean $(DOC)
	rubber --pdf --clean $(INS)

.PHONY: all clean distclean
