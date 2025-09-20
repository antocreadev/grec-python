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

clean : 
	rm -rf pb/__pycache__
	rm -f pb/*.py

run : 
	source .venv/bin/activate && python main.py


