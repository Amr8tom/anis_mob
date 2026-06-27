import os
from gtts import gTTS
from pydub import AudioSegment

scenes = [
    {
        "image": "daily-attendance.png",
        "text": "مع أنيس، الزائر يقدر يدخل ويخرج بسرعة باستخدام كيو آر الخاص بمساحتك. بدون زحمة عند الاستقبال، وبدون تسجيل يدوي كل مرة. وكل زيارة تتسجل تلقائيا في الداشبورد.",
    },
    {
        "image": "rooms.png",
        "text": "من لوحة واحدة تقدر تتابع الحضور والدخل، تضيف كل غرفة بإمكانياتها وسعرها، وتدير الحجوزات أو تعدل مواعيدها بسهولة. وكمان تقدر ترسل إشعارات لكل زوارك أو لمجموعة معينة منهم.",
    },
    {
        "image": "settings.png",
        "text": "ولأن ظهور المكان مهم، أنيس يساعدك تعرض مساحتك بشكل أفضل في التطبيق. اكتب وصف واضح، حدد الموقع، اختر المميزات، وارفع صور حقيقية للمكان. كل ما كانت الإعدادات والصور أفضل، كل ما ساعدت الزائر يثق في المكان ويقرر يزورك.",
    },
    {
        "image": "settings.png",
        "text": "أنيس. إدارة أسهل لمساحة عملك، وفر وقتك، ونظّم زوارك، واجذب عملاء أكثر.",
    }
]

out_dir = "/Users/eng.amralaa/StudioProjects/anis/docs/workspace-owner-guide/screenshots"
os.chdir(out_dir)

full_audio = AudioSegment.empty()
ffmpeg_input = ""

for i, scene in enumerate(scenes):
    print(f"Generating audio for scene {i+1}...")
    tts = gTTS(text=scene["text"], lang="ar", slow=False)
    audio_path = f"scene_{i+1}.mp3"
    tts.save(audio_path)
    
    # Load audio to get duration and append
    clip = AudioSegment.from_mp3(audio_path)
    
    # Add 1 second of silence after each clip for pacing
    clip_with_silence = clip + AudioSegment.silent(duration=1000)
    
    duration_sec = len(clip_with_silence) / 1000.0
    
    # Append to ffmpeg text
    ffmpeg_input += f"file '{scene['image']}'\n"
    ffmpeg_input += f"duration {duration_sec}\n"
    
    # Append to full audio
    full_audio += clip_with_silence

# For concat demuxer, the last file needs to be repeated without a duration
ffmpeg_input += f"file '{scenes[-1]['image']}'\n"

with open("input_dynamic.txt", "w") as f:
    f.write(ffmpeg_input)

full_audio.export("full_audio.mp3", format="mp3")
print("Audio and ffmpeg instructions generated successfully.")
