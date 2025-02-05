.PHONY: install

front:
	cd resources/js && npm install && npm run dev

front-build:
	cd resources/js && npm run build

front-start:
	cd resources/js && npm run start

