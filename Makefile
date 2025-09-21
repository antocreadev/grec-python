.PHONY : gen-original gen clean run

gen:
	source .venv/bin/activate && \
	python -m grpc.tools.protoc \
		--proto_path=proto \
		--python_betterproto2_out=pb \
		proto/*.proto \
		--python_betterproto2_opt=client_generation=sync_async \
		--python_betterproto2_opt=server_generation=async 

gen-original : 
	source .venv/bin/activate &&  python -m grpc_tools.protoc \
    --proto_path=proto \
    --python_out=pb \
    --grpc_python_out=pb \
    proto/*.proto
# 	Create __init__.py if it doesn't exist
	touch pb/__init__.py
# 	 Fix relative imports in generated files
	cd pb && sed -i '' 's/^import \([a-zA-Z_]*_pb2\) as/from . import \1 as/g' *.py

gen-php : 
	@echo "🔍 Vérification des dépendances..."
	@PROTOC=$$(which protoc); \
	if [ -z "$$PROTOC" ]; then \
		echo "❌ Erreur: protoc non trouvé. Installez-le avec: brew install protobuf"; \
		exit 1; \
	fi; \
	echo "✅ Protoc trouvé: $$PROTOC"; \
	GRPC_PHP_PLUGIN=$$(which grpc_php_plugin); \
	if [ -z "$$GRPC_PHP_PLUGIN" ]; then \
		echo "❌ Erreur: grpc_php_plugin non trouvé. Installez-le avec: brew install grpc"; \
		exit 1; \
	fi; \
	echo "✅ Plugin trouvé: $$GRPC_PHP_PLUGIN"; \
	echo "📁 Création du dossier de sortie..."; \
	mkdir -p php_out; \
	echo "🚀 Génération du code PHP..."; \
	protoc --proto_path=proto \
		--php_out=php_out \
		--grpc_out=php_out \
		--plugin=protoc-gen-grpc=$$GRPC_PHP_PLUGIN \
		proto/*.proto && echo "✅ Génération terminée dans php_out/"


clean : 
	rm -rf pb/__pycache__
	rm -f pb/*.py

run : 
	source .venv/bin/activate && python main.py

run-client-python:
	source .venv/bin/activate && python client.py

run-client-php:
	php client.php


