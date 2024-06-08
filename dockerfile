# Usar la imagen base oficial de Python 3.11.3
FROM python:3.11.3-slim

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    ffmpeg \
    libsm6 \
    libxext6 \
    libopencv-dev \
    cmake \
    build-essential \ 
    libssl-dev \
    zlib1g-dev \
    libbz2-dev \
    libreadline-dev \
    libsqlite3-dev \
    llvm \
    libncurses5-dev \ 
    libncursesw5-dev \
    xterm \
    xz-utils \
    tk-dev \ 
    libffi-dev \
    liblzma-dev \
    && rm -rf /var/lib/apt/lists/*

# Establecer el directorio de trabajo
WORKDIR /app

# Copiar los archivos de la aplicación
COPY ./People-Counting-in-Real-Time /app
# Instalar las dependencias de Python
RUN pip install --no-cache-dir -r requirements.txt
#RUN export DISPLAY=:1.0
#RUN xterm
# Comando para ejecutar la aplicación
#RUN pip uninstall -y opencv-python 
#RUN pip install opencv-python
CMD ["python3", "people_counter.py", "--prototxt", "detector/MobileNetSSD_deploy.prototxt", "--model", "detector/MobileNetSSD_deploy.caffemodel"]
#CMD ["/bin/bash"]
