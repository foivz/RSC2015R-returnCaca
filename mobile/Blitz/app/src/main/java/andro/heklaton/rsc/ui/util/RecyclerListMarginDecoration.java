package andro.heklaton.rsc.ui.util;

import android.graphics.Rect;
import android.support.v7.widget.RecyclerView;
import android.view.View;

public class RecyclerListMarginDecoration extends RecyclerView.ItemDecoration {

    private int space;

    public RecyclerListMarginDecoration(int space) {
        this.space = space;
    }

    @Override
    public void getItemOffsets(Rect outRect, View view, RecyclerView parent, RecyclerView.State state) {
        outRect.left = 0;
        outRect.right = 0;
        outRect.top = 0;
        outRect.bottom = space;
    }

}
