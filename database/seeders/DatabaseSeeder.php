<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResearchProject;
use App\Models\Literature;
use App\Models\Todo;
use App\Models\Note;
use App\Models\DailyRoutine;
use App\Models\Pomodoro;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(AchievementSeeder::class);

        // 课题
        ResearchProject::create([
            'title' => '基于深度学习的图像语义分割研究',
            'description' => '研究U-Net及其变体在医学图像分割中的应用，探索注意力机制的改进方案',
            'status' => 'in_progress',
            'progress' => 45,
            'start_date' => Carbon::now()->subMonths(3),
            'due_date' => Carbon::now()->addMonths(4),
        ]);
        ResearchProject::create([
            'title' => '跨模态检索算法优化',
            'description' => '基于对比学习的文本-图像跨模态检索方法研究',
            'status' => 'planning',
            'progress' => 10,
            'start_date' => Carbon::now()->subWeeks(2),
            'due_date' => Carbon::now()->addMonths(8),
        ]);
        ResearchProject::create([
            'title' => '毕业论文开题报告',
            'description' => '完成开题报告的撰写和答辩准备',
            'status' => 'completed',
            'progress' => 100,
            'start_date' => Carbon::now()->subMonths(5),
            'due_date' => Carbon::now()->subMonths(2),
        ]);

        // 文献
        $literatures = [
            ['title' => 'Attention Is All You Need', 'authors' => 'Vaswani et al.', 'journal' => 'NeurIPS', 'year' => 2017, 'status' => 'finished', 'rating' => 5, 'tags' => 'Transformer,注意力机制,NLP'],
            ['title' => 'U-Net: Convolutional Networks for Biomedical Image Segmentation', 'authors' => 'Ronneberger et al.', 'journal' => 'MICCAI', 'year' => 2015, 'status' => 'finished', 'rating' => 5, 'tags' => 'U-Net,图像分割,医学图像'],
            ['title' => 'BERT: Pre-training of Deep Bidirectional Transformers', 'authors' => 'Devlin et al.', 'journal' => 'NAACL', 'year' => 2019, 'status' => 'reading', 'rating' => 4, 'tags' => '预训练,NLP,BERT'],
            ['title' => 'ResNet: Deep Residual Learning for Image Recognition', 'authors' => 'He et al.', 'journal' => 'CVPR', 'year' => 2016, 'status' => 'finished', 'rating' => 5, 'tags' => 'CNN,残差网络,图像分类'],
            ['title' => 'Segment Anything', 'authors' => 'Kirillov et al.', 'journal' => 'ICCV', 'year' => 2023, 'status' => 'unread', 'rating' => 0, 'tags' => 'SAM,分割,基础模型'],
            ['title' => 'Vision Transformer (ViT)', 'authors' => 'Dosovitskiy et al.', 'journal' => 'ICLR', 'year' => 2021, 'status' => 'reading', 'rating' => 4, 'tags' => 'ViT,Transformer,视觉'],
        ];
        foreach ($literatures as $lit) {
            Literature::create($lit);
        }

        // 待办
        $todos = [
            ['title' => '完成实验对比表格', 'priority' => 'high', 'due_date' => Carbon::now()->addDays(2), 'category' => '论文'],
            ['title' => '整理文献综述第三章', 'priority' => 'high', 'due_date' => Carbon::now()->addDays(5), 'category' => '论文'],
            ['title' => '复现Baseline模型', 'priority' => 'medium', 'due_date' => Carbon::now()->addWeek(), 'category' => '实验'],
            ['title' => '准备组会PPT', 'priority' => 'medium', 'due_date' => Carbon::now()->addDays(3), 'category' => '汇报'],
            ['title' => '跑步30分钟', 'priority' => 'low', 'due_date' => Carbon::today(), 'category' => '生活'],
            ['title' => '阅读SAM论文', 'priority' => 'medium', 'due_date' => Carbon::now()->addDays(4), 'category' => '阅读'],
            ['title' => '提交月度进展报告', 'priority' => 'high', 'due_date' => Carbon::now()->subDay(), 'category' => '汇报'],
            ['title' => '买咖啡和零食', 'priority' => 'low', 'category' => '生活', 'completed' => true],
        ];
        foreach ($todos as $todo) {
            Todo::create($todo);
        }

        // 笔记
        Note::create([
            'title' => 'Transformer 核心机制笔记',
            'content' => "Self-Attention 机制:\n- Q, K, V 三个矩阵通过线性变换得到\n- Attention(Q,K,V) = softmax(QK^T/√dk)V\n- Multi-Head Attention 允许模型关注不同位置的不同表示子空间\n\n位置编码:\n- 使用正弦和余弦函数\n- PE(pos,2i) = sin(pos/10000^(2i/d_model))\n\nFeed Forward Network:\n- 两层全连接 + ReLU\n- FFN(x) = max(0, xW1+b1)W2+b2",
            'category' => '论文笔记',
            'tags' => 'Transformer,注意力,深度学习',
            'pinned' => true,
        ]);
        Note::create([
            'title' => 'PyTorch 常用代码片段',
            'content' => "# 模型保存与加载\ntorch.save(model.state_dict(), 'model.pth')\nmodel.load_state_dict(torch.load('model.pth'))\n\n# 学习率调度\nscheduler = torch.optim.lr_scheduler.CosineAnnealingLR(optimizer, T_max=100)\n\n# 混合精度训练\nscaler = torch.cuda.amp.GradScaler()\nwith torch.cuda.amp.autocast():\n    output = model(input)\n    loss = criterion(output, target)",
            'category' => '代码笔记',
            'tags' => 'PyTorch,深度学习,代码',
            'pinned' => true,
        ]);
        Note::create([
            'title' => '开题答辩准备要点',
            'content' => "1. 研究背景与意义（3分钟）\n2. 国内外研究现状（5分钟）\n3. 研究内容与技术路线（8分钟）\n4. 预期成果与创新点（3分钟）\n5. 进度安排（2分钟）\n\n注意事项：\n- PPT不超过30页\n- 准备老师可能问的问题\n- 带上打印版论文",
            'category' => '学习计划',
            'tags' => '开题,答辩,准备',
        ]);

        // 作息记录（近两周）
        for ($i = 14; $i >= 0; $i--) {
            DailyRoutine::create([
                'date' => Carbon::now()->subDays($i),
                'wake_time' => sprintf('%02d:%02d', rand(6, 8), rand(0, 59)),
                'sleep_time' => sprintf('%02d:%02d', rand(22, 23), rand(0, 59)),
                'study_hours' => rand(4, 10) + (rand(0, 1) * 0.5),
                'exercise_minutes' => rand(0, 1) ? rand(20, 60) : 0,
                'mood' => rand(2, 5),
                'summary' => ['写论文，进展顺利', '调试代码，bug较多', '读了两篇论文', '组会汇报', '整理数据', '复习课程', '实验跑通了！', '效率一般', '状态很好，写了很多', '去图书馆学习', '和导师讨论', '准备材料', '休息日', '效率不高，需调整', '实验结果不错'][array_rand(['写论文，进展顺利', '调试代码，bug较多', '读了两篇论文', '组会汇报', '整理数据', '复习课程', '实验跑通了！', '效率一般', '状态很好，写了很多', '去图书馆学习', '和导师讨论', '准备材料', '休息日', '效率不高，需调整', '实验结果不错'])],
            ]);
        }

        // 番茄钟
        for ($i = 20; $i >= 0; $i--) {
            $count = rand(2, 8);
            for ($j = 0; $j < $count; $j++) {
                Pomodoro::create([
                    'date' => Carbon::now()->subDays($i),
                    'task' => ['写论文', '读文献', '跑实验', '写代码', '整理数据', '做PPT'][array_rand(['写论文', '读文献', '跑实验', '写代码', '整理数据', '做PPT'])],
                    'duration' => [25, 25, 25, 45][array_rand([25, 25, 25, 45])],
                    'completed' => true,
                ]);
            }
        }
    }
}
